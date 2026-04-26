<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $user->update([
            'role' => $request->role
        ]);

        return back();
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}