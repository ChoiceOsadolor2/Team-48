<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TemporaryPasswordMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = mb_strtolower(trim($request->string('email')->toString()));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->with('status', 'If that account exists, a temporary password has been emailed.');
        }

        $temporaryPassword = Str::password(12, true, true, false, false);
        $originalPassword = $user->password;
        $originalRememberToken = $user->remember_token;

        try {
            $user->forceFill([
                'password' => Hash::make($temporaryPassword),
                'remember_token' => Str::random(60),
            ])->save();

            Mail::to($user->email)->send(new TemporaryPasswordMail($user, $temporaryPassword));
        } catch (Throwable $exception) {
            $user->forceFill([
                'password' => $originalPassword,
                'remember_token' => $originalRememberToken,
            ])->save();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not send the temporary password email right now. Please try again.']);
        }

        return back()->with('status', 'If that account exists, a temporary password has been emailed.');
    }
}
