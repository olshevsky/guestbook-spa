<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('User/Index', [
            'users' => User::paginate(10),
            'isAdmin' => $this->isAdmin(),
            'user' => \Auth::user()
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:4|max:255'
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return to_route('user');
    }

    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password
            ],
        ]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:4|max:255'
        ]);

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        if($request->get('password'))
            $user->password = Hash::make($request->get('password'));
        $user->save();

        return to_route('user');
    }

    public function destroy(User $user, Request $request)
    {
        $user->delete();

        return to_route('user');
    }

    private function isAdmin(): bool
    {
        return \Auth::user()?->is_admin ?? false;
    }
}
