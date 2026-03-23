<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')->paginate(9);
        return view('admin.pages.users', compact('users'));
    }

    public function upgrade(Request $request)
    {
        $userId = $request->user_id;
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy người dùng.'
            ], 404);
        }

        $staffRoleId = Role::where('name', 'staff')->value('id');
        $customerRoleId = Role::where('name', 'customer')->value('id');

        if ($user->role_id == $customerRoleId) {
            $user->role_id = $staffRoleId;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Đã update thành nhân viên.'
            ]);
        }
        // Nếu hiện tại là staff thì chuyển ngược về customer
        if ($user->role_id == $staffRoleId) {
            $user->role_id = $customerRoleId;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Đã chuyển về khách hàng.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không thể cập nhật role.'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $userId = $request->user_id;
        $status = $request->status;

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => ' Không tìm thấy người dùng',
            ]);
        }
        $user->status = $status;
        $user->save();
        return response()->json([

            'status' => true,
            'message' => 'Trạng thái người dùng đã được cập nhật  '
        ]);
    }
}
