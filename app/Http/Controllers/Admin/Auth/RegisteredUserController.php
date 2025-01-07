<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CredentialChangeMail;
use App\Mail\WelcomeUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'confirm_password' => ['required', 'same:password'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'location' => str_replace(' ', '-', $request->name) . rand(111111111, 99999999),
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            event(new Registered($user));
            // Auth::login($user);

            $credetials = [
                'reason' => 'Account Created At',
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];

            $em = sendEmail($credetials);
            return redirect(RouteServiceProvider::HOME);
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }

        //  \Mail::to($user->email)->send(new CredentialChangeMail($credetials));


    }
}
