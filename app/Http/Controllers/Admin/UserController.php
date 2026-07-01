<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->verified()->with('vendorProfile')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('user_code', 'like', '%'.$search.'%')
                    ->orWhere('dxn_member_code', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('dxn_verified')) {
            if ($request->dxn_verified === 'yes') {
                $query->where('is_dxn_verified', true);
            } elseif ($request->dxn_verified === 'no') {
                $query->where(function ($builder) {
                    $builder->where('is_dxn_verified', false)->orWhereNull('is_dxn_verified');
                });
            }
        }

        if ($request->filled('dxn_tag_color')) {
            $query->where('dxn_tag_color', $request->dxn_tag_color);
        }

        $users = $query->paginate(15)->appends($request->query());

        return view('admin.users.index', [
            'users' => $users,
            'dxnTagColors' => config('dxn.default_tag_colors', []),
            'stats' => [
                'total' => User::verified()->count(),
                'admins' => User::verified()->where('role', 'admin')->count(),
                'vendors' => User::verified()->where('role', 'vendor')->count(),
                'with_avatars' => User::verified()->whereNotNull('avatar')->count(),
                'suspended' => User::verified()->where('is_suspended', true)->count(),
                'dxn_verified' => User::verified()->where('is_dxn_verified', true)->count(),
            ],
        ]);
    }

    public function edit(User $user): View
    {
        if (! $user->hasVerifiedEmail()) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $removeAvatar = (bool) ($data['remove_avatar'] ?? false);

        unset($data['avatar'], $data['remove_avatar'], $data['dxn_badge_image']);

        $data['phone'] = $data['phone'] ?? null;
        $data['user_code'] = $data['user_code'] ?? null;

        if (! empty($data['is_dxn_verified']) && filled($data['dxn_member_code'] ?? null)) {
            $data['dxn_verified_at'] = $user->dxn_verified_at ?? now();
            $data['user_code'] = $data['dxn_member_code'];
        } elseif (empty($data['is_dxn_verified'])) {
            $data['dxn_verified_at'] = null;
        }

        $user->fill($data);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('users/avatars', 'public');
        } elseif ($removeAvatar && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('dxn_badge_image')) {
            if ($user->dxn_badge_image) {
                Storage::disk('public')->delete($user->dxn_badge_image);
            }
            $user->dxn_badge_image = $request->file('dxn_badge_image')->store('dxn_badges', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User profile updated successfully.');
    }

    public function suspend(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'You cannot suspend your own administrator account.');
        }

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Admin accounts cannot be suspended.');
        }

        $user->is_suspended = ! $user->is_suspended;
        $user->save();

        if ($user->is_suspended) {
            \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $status = $user->is_suspended ? 'suspended' : 'activated';

        return redirect()->back()->with('success', "User account has been {$status}.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'You cannot delete your own administrator account.');
        }

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Admin accounts cannot be deleted.');
        }

        \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->back()->with('success', 'User has been deleted.');
    }
}
