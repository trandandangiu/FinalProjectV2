<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(
            'user',
            'orderItems',
            'shippingAddress',
            'payment'
        )->orderByDesc('id')->get();
        return view('admin.pages.orders', compact('orders'));
    }

    public function confirmOrder(Request $request)
    {

        $order = Order::find($request->id);

        if ($order) {
            $order->status = 'processing';
            $order->save();

            return response()->json([
                'status' => true,
                'message' => 'Đơn hàng đã được xác nhận thành công.'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Đơn hàng không tồn tại.'
        ]);
    }

    public function showOrderDetail($id)
    {
        $order = Order::with(
            'user',
            'orderItems.product',
            'shippingAddress',
            'payment'
        )->find($id);

   
        return view('admin.pages.orders-detail', compact('order'));
    }

    public function sendInvoiceMail(Request $request)
    {
        $id = $request->id;
             $order = Order::with(
            'user',
            'orderItems.product',
            'shippingAddress',
            'payment'
        )->find($id);

        try {  
            Mail::send('admin.emails.invoice', compact('order'), function ($message) use ($order) {
                $message->to($order->user->email)
                        ->subject('Hóa đơn đặt hàng #' . $order->shippingAddress->full_name);
            });
            return response()->json([
                'status' => true,
                'message' => 'Hóa đơn đã được gửi qua email thành công.'
            ]);
        }  catch (\Throwable $th) {  
            return response()->json([
                'status' => false,
                'message' => 'Gửi email thất bại: ' . $th->getMessage()
            ]);
        }
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::find($request->id);

        if ($order) {
            $order->status = 'canceled';
            $order->save();

            return response()->json([
                'status' => true,
                'message' => 'Đơn hàng đã được hủy thành công.'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Đơn hàng không tồn tại.'
        ]);
    }
}
