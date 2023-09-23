<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function allNotifications()
    {
        $admin = Admin::find(1);
        if($admin) {
            return response()->json(['notifications' => $admin->notifications]);
        }
        return response()->json(['message' => 'error']);
    }

    public function unreadNotifications()
    {
        $admin = Admin::find(1);
        if($admin) {
            return response()->json(['unreadNotifications' => $admin->unreadNotifications]);
        }
        return response()->json(['message' => 'error']);
    }

    public function markAllAsRead()
    {
        $admin = Admin::find(1);
        $admin->unreadNotifications->markAsRead();
        if($admin) {
            return response()->json(['message' => 'marked all as read']);
        }
        return response()->json(['message' => 'error']);
    }

    public function markAsRead($id)
    {
        $notification = DB::table('notifications')->where('id',$id)->update(['read_at' => now()]);
        if($notification) {
            return response()->json(['message' => 'marked as read']);
        }
        return response()->json(['message' => 'error']);
    }

    public function deleteAll()
    {
        $admin = Admin::find(1);
        $admin->notifications()->delete();
        if($admin) {
            return response()->json(['message' => ' all notifications deleted']);
        }
        return response()->json(['message' => 'error']);
    }

    public function delete($id)
    {
        $notification = DB::table('notifications')->where('id',$id)->delete();
        if($notification) {
            return response()->json(['message' => 'notification deleted']);
        }
        return response()->json(['message' => 'error']);
    }
}
