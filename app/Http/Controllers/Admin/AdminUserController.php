<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('bookings')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        // Prevent removing your own admin role
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
