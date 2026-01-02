<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Flasher\Toastr\Prime\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use function Flasher\Toastr\Prime\toastr;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('clients.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Định dạng email không hợp lệ',
                'email.exists' => 'Email không tồn tại trong hệ thống'
            ]
        );

        $status =  Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            toastr()->success('Liên kết đặt lại mật khẩu đã được gửi tới email của bạn');
            return back();
        }
        toastr()->error('Không thể gửi email');
        return back()->withErrors(['email' => __($status)]);
    }
}
