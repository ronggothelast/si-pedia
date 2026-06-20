<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $view = $user->role === 'admin' ? 'pages.profil_admin' : 'pages.profil_user';
        return view($view, compact('user'));
    }

    public function edit()
    {
        return view('pages.update_profil', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6',
            'avatar'   => 'nullable|image|max:10240',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $user->update($data);

        return redirect()->route('profile.show')->with('status', 'Profile updated.');
    }
}
