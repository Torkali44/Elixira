<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function suspend(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot suspend an admin.');
        }
        
        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $status = $user->is_suspended ? 'suspended' : 'activated';
        return redirect()->back()->with('success', "User account has been {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete an admin.');
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'User has been deleted.');
    }
}
