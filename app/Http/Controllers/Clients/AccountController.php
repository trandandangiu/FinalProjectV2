<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Address;
use App\Models\Order;

use function Flasher\Toastr\Prime\toastr;

class AccountController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        $orders = Order::where('user_id',$user->id)->orderBy('created_at', 'desc')->get();
        return view('clients.pages.account', compact('user', 'addresses', 'orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            "ltn__name" => 'required|string|max:255',
            "ltn__phone_number" => 'nullable|string|max:15',
            "ltn__address" => 'nullable|string|max:255',
            "avatar" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Xử lý avatar: Chỉ chạy khi có file upload lên
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $avatarPath = $file->storeAs('uploads/users', $filename, 'public');

            // Cập nhật thuộc tính avatar
            $user->avatar = $avatarPath;
        }

        // Gán giá trị trực tiếp để tránh lỗi Mass Assignment của update()
        $user->name = $request->input('ltn__name');
        $user->phone_number = $request->input('ltn__phone_number');
        $user->address = $request->input('ltn__address');

        // Quan trọng: Gọi save() trên instance cụ thể
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công',
            'avatar' => asset('storage/' . $user->avatar),
        ]);
    }

    // Change password
    public function ChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Mật khẩu không được bỏ trống',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'confirm_new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'confirm_new_password.same' => 'Mật khẩu nhập lại không khớp',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => ['Mật khẩu hiện tại không khớp!']]], 422);
        }
        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mật khẩu thành công',
        ]);
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|min:10',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        if ($request->has('is_default')) {
            ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
        }
        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'default' => $request->has('is_default') ? 1 : 0
        ]);
        return back()->with('success', 'Địa chỉ đã được thêm');
    }

    public function updatePrimaryAddress($id)
    {
        $address = ShippingAddress::where('id', $id)->where('user_id', Auth::id())->FirstOrFail();

        // set all address this user default = 0
        ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);

        // update address selected =. default =1 
        $address->update(['default' => 1]);

        toastr()->success('Địa chỉ đã được cập nhật !');
        return back();
    }

    public function deleteAddress($id)
    {
        ShippingAddress::where('id', $id)->where('user_id', Auth::id())->delete();
        toastr()->success('Địa chỉ đã được xóa');
        return back();
    }
}
