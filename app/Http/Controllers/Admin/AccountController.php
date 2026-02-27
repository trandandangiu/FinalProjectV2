<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
  public function index()
  {
    $contacts = Contact::orderByDesc('created_at')->get();
    $user = Auth::guard('admin')->user();

    return view('admin.pages.profile', compact('contacts', 'user'));
  }

  public function updateProfile(Request $request)
  {
    $user = Auth::guard('admin')->user();
    $type = $request->type;

    try {
      if ($type === "profile") {
        $request->validate([
          'name' => 'required|string|max:255',
          'phone' => 'required|regex:/^(0\d{9})$/',
          'address' => 'required|string|max:255',
        ]);

        $user->update([
          'name' => $request->name,
          'phone_number' => $request->phone,
          'address' => $request->address,
        ]);

        return response()->json(['status' => true, 'message' => 'Cập nhật thông tin thành công', 'data' => $user]);
      } elseif ($type === "password") {
        $request->validate([
          'current_password' => 'required',
          'new_password' => 'required|min:6',
          'confirm_password' => 'required|same:new_password'
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
          return response()->json(['status' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['status' => true, 'message' => 'Đổi mật khẩu thành công']);
      } elseif ($type === "avatar") {
        if ($request->hasFile('avatar')) {
          // Xóa ảnh cũ nếu tồn tại
          if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
          }

          $path = $request->file('avatar')->store('avatars', 'public');
          $user->update(['avatar' => $path]);

          return response()->json([
            'status' => true,
            'message' => 'Cập nhật ảnh đại diện thành công',
            'avatar_url' => asset('storage/' . $path)
          ]);
        }
      }
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }

    return response()->json(['status' => false, 'message' => 'Yêu cầu không hợp lệ']);
  }
}
