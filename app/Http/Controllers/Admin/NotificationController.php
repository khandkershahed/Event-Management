<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth('admin')->user()->notifications()->latest()->paginate(15);

        return view('admin.pages.notifications.index', compact('notifications'));
    }

    public function read(DatabaseNotification $notification): RedirectResponse
    {
        $admin = auth('admin')->user();

        abort_unless($notification->notifiable_type === $admin::class && (int) $notification->notifiable_id === (int) $admin->id, 403);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('admin.notifications.index');

        return redirect($url);
    }
}
