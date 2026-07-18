<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Notification $notification)
    {
        $this->ensureOwnsNotification($notification);

        $notification->forceFill(['is_read' => true])->save();

        $unreadCount = auth()->user()->unreadNotifications()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
            ]);
        }

        return redirect()->back();
    }

    public function open(Notification $notification): RedirectResponse
    {
        $this->ensureOwnsNotification($notification);

        $notification->forceFill(['is_read' => true])->save();

        $target = $notification->url ?: route('home');

        // Prefer relative app paths so redirects stay on this domain.
        if (is_string($target) && str_starts_with($target, url('/'))) {
            $target = '/'.ltrim(substr($target, strlen(rtrim(url('/'), '/'))), '/');
        }

        return redirect()->to($target ?: route('home'));
    }

    public function markAllAsRead(Request $request)
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }

        return redirect()->back()->with('success', __('app.notifications_marked_read'));
    }

    private function ensureOwnsNotification(Notification $notification): void
    {
        // MySQL may return user_id as string; strict !== then wrongly 403s on production.
        if ((int) $notification->user_id !== (int) auth()->id()) {
            abort(403);
        }
    }
}
