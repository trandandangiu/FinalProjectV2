<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('is_read', 0)->latest('created_at')->get();
        foreach ($notifications as $noti) 
        {
            if($noti->type ==='order')  $noti->title = 'Co don hang moi';
            else if($noti->type ==='contact')  $noti->title = 'Bạn có một phản hồi mới';
            else if($noti->wishlist ==='wishlist')  $noti->title = 'San pham  quan tam ';
        }
        return view('admin.pages.notifications', compact('notifications'));
    }
}
