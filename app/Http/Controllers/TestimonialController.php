<?php

namespace App\Http\Controllers;

use App\Models\AvatarOption;
use App\Models\NewsletterSubscriber;
use App\Models\Review;
use App\Models\User;
use App\Support\UserNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'direct');

        $reviews = Review::where('status', 'approved')
            ->where('type', $tab)
            ->latest()
            ->paginate(12);

        $avatarOptions = AvatarOption::active()->ordered()->get();

        return view('testimonials.index', compact('tab', 'reviews', 'avatarOptions'));
    }

    public function store(Request $request)
    {
        $avatarUrls = AvatarOption::active()->pluck('image_url')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'age' => 'required|string',
            'gender' => 'required|string',
            'rating' => 'required|string',
            'content' => 'required|string',
            'avatar' => [
                'required',
                'string',
                Rule::in($avatarUrls),
            ],
            'type' => 'required|string|in:direct',
        ]);

        try {
            Review::create([
                'type' => $request->type,
                'avatar' => $request->avatar,
                'name' => $request->name,
                'age' => $request->age,
                'skin_type' => $request->gender,
                'rating' => intval($request->rating),
                'content' => $request->content,
                'status' => 'pending',
            ]);

            // Notify Admins
            try {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    UserNotifier::send($admin->id, 'new_review', [
                        'name' => $request->name,
                    ], route('admin.reviews.index'));
                }
            } catch (\Throwable $e2) {
                \Log::error('Review notification failed: '.$e2->getMessage());
            }
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('testimonials.index', ['tab' => 'write'])
                ->with('error', 'We could not save your reflection. Please try again in a moment.')
                ->withInput();
        }

        return redirect()
            ->route('testimonials.index', ['tab' => 'write'])
            ->with('success', 'Your reflection has been sent successfully and is pending approval.');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->email;

        try {
            NewsletterSubscriber::firstOrCreate(['email' => $email]);

            Mail::raw("Thank you for subscribing to Elixira's Whisper! Stay tuned for exclusive launches and wellness tips.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Welcome to Elixira — The Whisper');
            });

            Mail::raw('A new user has subscribed to the newsletter: '.$email, function ($message) {
                $message->to('admin@elixira.com')
                    ->subject('New Newsletter Subscriber');
            });
        } catch (\Exception $e) {
            \Log::error('Newsletter subscription failed: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'You have successfully joined The Whisper community!');
    }
}
