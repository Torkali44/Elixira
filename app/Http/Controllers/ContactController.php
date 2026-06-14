<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\User;
use App\Support\UserNotifier;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::create([
            'user_id' => $request->user()?->id,
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'reason' => $request->validated('reason'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
        ]);

        $admins = User::query()->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotifier::send($admin->id, 'contact_message_received', [
                'name' => $message->name,
                'subject' => $message->subject,
            ], route('admin.contact-messages.show', $message));
        }

        return redirect()->route('contact')->with('success', __('contact.form_success'));
    }
}
