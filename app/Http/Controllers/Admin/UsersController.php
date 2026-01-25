<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
  public function index(Request $request) {
        $users = User::with('role')->paginate(9);
    return view('admin.pages.users', compact('users'));
  }

public function upgrade(Request $request)
{
    $userId = $request->user_id;
    $user = User::find($userId);

    if(!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy người dùng.'
        ], 404);
    }

    $user->role_id = 2;
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Đã update thành nhân viên.'
    ]);
}
  }

