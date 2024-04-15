<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function edit()
    {
        try {
            // Retrieve the authenticated admin
            $user = Auth()->user();

            return view('user.profile', compact('user'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update admin profile
        $user = auth()->user();
        $user_ = User::find($user->id);
        $user_->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? bcrypt($validatedData['password']) : $user->password,
        ]);

        return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully!');
    }
}
