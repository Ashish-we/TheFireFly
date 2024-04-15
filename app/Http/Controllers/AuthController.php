<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function register_form()
    {
        return view('user.register');
    }

    public function login_form()
    {
        return view('user.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::default()]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255']
        ]);

        Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));

        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $user = User::find($user->id); // to remove the error of undefined method task() introduced using above $user
        $tasks = $user->task()->orderBy('id', 'desc')->paginate(10);
        // $tasks = Task::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        $i = 0;
        return view('user.dashboard', compact('tasks', 'i'));
    }
}
