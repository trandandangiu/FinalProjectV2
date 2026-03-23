<?php

namespace App\Http\Controllers\Clients;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // Lấy lịch sử chat (người dùng hoặc khách)
    public function fetchMessages(Request $request)
    {
        if (Auth::check()) {
            // Nếu đã đăng nhập thì lấy theo user_id
            $msgs = ChatMessage::where('user_id', Auth::id())
                ->orderBy('created_at')
                ->get();
        } else {
            // Nếu là khách thì lấy theo guest_token trong cookie
            $token = $request->cookie('chat_token');
            $msgs = $token
                ? ChatMessage::where('guest_token', $token)
                ->orderBy('created_at')
                ->get()
                : collect();
        }

        return response()->json($msgs);
    }

    public function sendMessage(Request $request)
    {
        // 1) Validate dữ liệu đầu vào
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userId = Auth::id();

        // 2) Xử lý guest token (cookie)
        $guestToken = null;
        if (!$userId) {
            $guestToken = $request->cookie('chat_token');
            if (!$guestToken) {
                $guestToken = 'guest_' . Str::random(32);
                Cookie::queue('chat_token', $guestToken, 60 * 24 * 180);
            }
        }

        // 3) Lưu tin nhắn user
        $userMsg = ChatMessage::create([
            'user_id'     => $userId,
            'guest_token' => $userId ? null : $guestToken,
            'sender'      => 'user',
            'message'     => $request->message,
        ]);

        // Chuẩn bị prompt với danh sách sản phẩm
        $products = Product::where('stock', '>', 0)
            ->get(['name', 'price', 'unit', 'description'])
            ->map(fn($p) => "{$p->name} - {$p->price}/{$p->unit}")
            ->toArray();

        $productList = implode("\n", $products);

        $prompt = "Bạn là trợ lý bán hàng cho website fitness. "
            . "Dưới đây là danh sách một số sản phẩm hiện có:\n"
            . $productList
            . "\nHãy trả lời ngắn gọn, trung thực, chỉ dùng thông tin trong danh sách sản phẩm nếu cần.";

        // Lấy lịch sử chat gần nhất
        $history = ChatMessage::query()
            ->where(function ($q) use ($userId, $guestToken) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('guest_token', $guestToken);
                }
            })
            ->orderBy('created_at', 'asc')
            ->latest()
            ->limit(6)
            ->get();

        $contents = [];
        foreach ($history as $msg) {
            $contents[] = [
                "role"  => $msg->sender === "user" ? "user" : "model",
                "parts" => [["text" => $msg->message]],
            ];
        }
        $contents[] = [
            "role" => "user",
            "parts" => [["text" => $request->message]]
        ];

        $aiReplyText = "Xin lỗi, hiện tại tôi chưa được cấu hình.";

        // 4) Gọi API Gemini
        if (env('GOOGLE_GEMINI_API_KEY')) {
            try {
                $url_apikey = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=' . env('GOOGLE_GEMINI_API_KEY');

                $payload = [
                    "systemInstruction" => [
                        "role" => "system",
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ],
                    "contents" => [
                        [
                            "role" => "user",
                            "parts" => [
                                ["text" => $request->message]
                            ]
                        ]
                    ]
                ];
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url_apikey, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Gemini response', $data);
                    $aiReplyText = $data['candidates'][0]['content']['parts'][0]['text']
                        ?? "Xin lỗi, tôi chưa hiểu câu hỏi.";
                } else {
                    $aiReplyText = "Xin lỗi, AI không thể xử lý lúc này.";
                    Log::error('AI API error', ['response' => $response->json()]);
                }
            } catch (\Throwable $e) {
                Log::error('Gemini exception', [$e->getMessage()]);
                $aiReplyText = "Xin lỗi, hiện tại không thể kết nối AI.";
            }
        }

        // 5) Lưu phản hồi bot
        $botMsg = ChatMessage::create([
            'user_id'     => $userId,
            'guest_token' => $userId ? null : $guestToken,
            'sender'      => 'bot',
            'message'     => $aiReplyText,
        ]);

        // 6) Trả về JSON cho frontend
        Log::info('Gemini status', [$response->status()]);
        Log::info('Gemini body', [$response->body()]);

        return response()->json([
            'user' => $userMsg,
            'bot'  => $botMsg,
        ]);
    }
}
