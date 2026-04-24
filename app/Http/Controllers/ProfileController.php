<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateProfileAvatarRequest;
use App\Models\AvatarOption;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $ordersQuery = $this->accountOrdersQuery($user);
        $featuredItems = Item::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'accountStats' => [
                'total_orders' => (clone $ordersQuery)->count(),
                'active_orders' => (clone $ordersQuery)
                    ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                    ->count(),
                'delivered_orders' => (clone $ordersQuery)->where('status', 'delivered')->count(),
                'total_spent' => (clone $ordersQuery)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount'),
            ],
            'latestOrder' => (clone $ordersQuery)->latest()->first(),
            'featuredItems' => $featuredItems,
        ]);
    }

    public function orders(Request $request): View
    {
        $user = $request->user();
        $orders = $this->accountOrdersQuery($user)
            ->withCount('orderItems')
            ->with(['orderItems.item:id,name,image'])
            ->latest()
            ->paginate(8);

        return view('profile.orders.index', compact('orders'));
    }

    public function avatarOptions(Request $request): View
    {
        $avatarOptions = AvatarOption::active()->ordered()->get();

        return view('profile.avatar-options', [
            'avatarOptions' => $avatarOptions,
            'user' => $request->user()->load('avatarOption'),
        ]);
    }

    public function updateAvatarOption(UpdateProfileAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->avatar_option_id = $request->validated('avatar_option_id');
        $user->save();

        return Redirect::route('profile.avatar-options')->with('status', 'avatar-updated');
    }

    /**
     * Display an order that belongs to the current user.
     */
    public function showOrder(Request $request, Order $order): View
    {
        abort_unless($this->canAccessOrder($request->user(), $order), 404);

        $order->load(['orderItems.item']);

        return view('profile.orders.show', [
            'order' => $order,
        ]);
    }

    public function invoice(Request $request, Order $order): View
    {
        abort_unless($this->canAccessOrder($request->user(), $order), 404);

        $order->load(['orderItems.item']);

        return view('profile.orders.invoice', [
            'order' => $order,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $removeAvatar = (bool) ($data['remove_avatar'] ?? false);

        if (!empty($data['phone_number'])) {
            $data['phone'] = ($data['country_code'] ?? '+966') . ltrim($data['phone_number'], '0');
        } else {
            $data['phone'] = null;
        }

        $data['user_code'] = $data['user_code'] ?? null;

        unset($data['phone_number'], $data['country_code'], $data['avatar'], $data['remove_avatar']);

        $user->fill($data);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('users/avatars', 'public');
            $user->avatar_option_id = null;
        } elseif ($removeAvatar && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->role === 'admin') {
            return Redirect::route('profile.edit')->with('error', 'Administrator accounts cannot delete themselves from the system.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    protected function accountOrdersQuery(User $user): Builder
    {
        return Order::query()->where(function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);

            if ($user->phone || $user->user_code) {
                $query->orWhere(function (Builder $guestOrders) use ($user) {
                    $guestOrders
                        ->whereNull('user_id')
                        ->where(function (Builder $match) use ($user) {
                            if ($user->phone) {
                                $match->where('customer_phone', $user->phone);
                            }

                            if ($user->user_code) {
                                if ($user->phone) {
                                    $match->orWhere('user_code', $user->user_code);
                                } else {
                                    $match->where('user_code', $user->user_code);
                                }
                            }
                        });
                });
            }
        });
    }

    protected function canAccessOrder(User $user, Order $order): bool
    {
        if ($order->user_id === $user->id) {
            return true;
        }

        if ($order->user_id !== null) {
            return false;
        }

        return ($user->phone && $order->customer_phone === $user->phone)
            || ($user->user_code && $order->user_code === $user->user_code);
    }
}
