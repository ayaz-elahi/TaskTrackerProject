<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class SecurityQuestionController extends Controller
{
    public function showEmailForm(): View
    {
        return view('auth.forgot-password-security');
    }

    public function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user->security_question) {
            return back()->withErrors(['email' => 'This account does not have a security question set up.']);
        }

        return redirect()->route('password.security.question', ['email' => $request->email]);
    }

    public function showQuestionForm(Request $request): View
    {
        $email = $request->query('email');
        $user = User::where('email', $email)->firstOrFail();

        return view('auth.reset-password-security', ['email' => $email, 'question' => $user->security_question]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'security_answer' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        // Simple comparison (case-insensitive for better UX)
        if (strtolower(trim($request->security_answer)) !== strtolower(trim($user->security_answer))) {
            return back()->withErrors(['security_answer' => 'Incorrect answer.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}
