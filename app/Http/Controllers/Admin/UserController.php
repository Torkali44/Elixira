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
        $query = User::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('user_code', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('avatar_status')) {
            if ($request->avatar_status === 'with-avatar') {
                $query->whereNotNull('avatar');
            }

            if ($request->avatar_status === 'missing-avatar') {
                $query->whereNull('avatar');
            }
        }

        $users = $query->paginate(15)->appends($request->query());

        return view('admin.users.index', [
            'users' => $users,
            'stats' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'with_avatars' => User::whereNotNull('avatar')->count(),
                'suspended' => User::where('is_suspended', true)->count(),
            ],
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $removeAvatar = (bool) ($data['remove_avatar'] ?? false);

        unset($data['avatar'], $data['remove_avatar']);

        $data['phone'] = $data['phone'] ?? null;
        $data['user_code'] = $data['user_code'] ?? null;

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

        $user->is_suspended = !$user->is_suspended;
        $user->save();

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

        $user->delete();

        return redirect()->back()->with('success', 'User has been deleted.');
    }
}
