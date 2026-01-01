<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Mail\ActivationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('clients.pages.register');
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|', 
            ],
            [
                'name.required' => 'Tên không được để trống',
                'email.required' => 'Email là bắt buộc',
                'email.email' => 'Định dạng email không hợp lệ',
                'email.unique' => 'Email đã được sử dụng',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',

            ]
        );

        // Check if email exists
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            if ($existingUser->status === 'pending') {
                toastr()->error('Tài khoản đã được đăng ký và đang đợi kích hoạt. Vui lòng kiểm tra email của bạn.');
                return redirect()->route('register');
            }
            toastr()->error('Email đã được sử dụng.');
            return redirect()->route('register');
        }

        // Create activation token
        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'role_id' => 4,
            'activation_token' => $token,
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        toastr()->success('Đăng ký tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
        return redirect()->route('login'); // Nên redirect đến login thay vì back
    }

    public function activate($token)
    {

    }
}