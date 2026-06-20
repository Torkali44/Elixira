<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(25);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->back()->with('success', 'Subscriber removed successfully.');
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'subscribers' => 'required|array',
            'subscribers.*' => 'exists:newsletter_subscribers,id',
        ]);

        $subscriberEmails = NewsletterSubscriber::whereIn('id', $request->subscribers)->pluck('email');
        $subject = $request->subject;
        $content = $request->input('content');

        $successCount = 0;
        foreach ($subscriberEmails as $email) {
            try {
                Mail::raw($content, function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                });
                $successCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to send newsletter email to {$email}: ".$e->getMessage());
            }
        }

        return redirect()->back()->with('success', "Emails successfully queued/sent to {$successCount} subscribers.");
    }
}
