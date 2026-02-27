<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {

    $contacts = Contact::orderByDesc('created_at')->get();

        return view('admin.pages.contact', compact('contacts'));
    }

    public function replyContact(Request $request)
  {
    $id = $request->contact_id;
    $content = $request->message; 
    $email = $request->email;

    if (empty($content)) {
      return response()->json(['status' => false, 'message' => 'Nội dung không được để trống']);
    }

    try {
     
      Mail::send('admin.emails.reply-contact', ['content' => $content], function ($m) use ($email) {
        $m->to($email)->subject('Trả lời liên hệ từ Admin');
      });

    
      Contact::where('id', $id)->update(['is_replied' => 1]);

      return response()->json([
        'status' => true,
        'message' => 'Gửi email trả lời liên hệ thành công.'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'message' => 'Gửi email thất bại: ' . $th->getMessage()
      ]);
    }
  }

}
