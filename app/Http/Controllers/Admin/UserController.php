<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.users.index', ['users' => $users]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'is_teacher' => ['boolean'],
        ]);

        $user->is_teacher = $request->boolean('is_teacher');
        $user->save();

        return redirect()->route('admin.users.index');
    }
}
