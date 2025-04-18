<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;
use App\Mail\Websitemail;

class UserController extends Controller
{
    public function dashboard() {
        return view('user.dashboard');
    }
    public function register() {
        return view('user.register');
    }
    public function login() {
        return view('user.login');
    }
    public function register_submit(Request $request) {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $token = hash('sha256', time());
        $user->token = $token;
        $user->save();

        $verification_link = url('register_verify/'.$token.'/'.$request->email);
        $subject = 'Register Verification';
        $message = "To complete your registration, please click the link below.<br>";
        $message .= "<a href='".$verification_link."'>Click Here</a>";

        \Mail::to($request->email)->send(new Websitemail($subject,$message));
        return redirect()->back()->with('success', 'We have sent a link to your email. Please check your inbox.');
    }
    public function register_verify($token, $email) {
        $user = User::where('email', $email)->where('token', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Token or Email not correct.');
        }
        $user->token = '';
        $user->status = 1;
        $user->update();

        return redirect()->route('login')->with('success', 'Your account has been verified.');
    }
    public function login_submit(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
            'status' => 1,
        ];
        if (Auth::guard('web')->attempt($data)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')->with('error', 'Invalid Credentials');
    }
    public function logout() {
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
    public function forget_password() {
        return view('user.forget-password');
    }
    public function forget_password_submit(Request $request) {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $user = User::where('email', $request->email)->where('status', 1)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Email not found.');
        }
        $token = hash('sha256', time());
        $user->token = $token;
        $user->update();
        $reset_link = url('reset-password/'.$token.'/'.$request->email);
        $subject = 'Password Reset';
        $message = "Click the link below to reset your password.<br>";
        $message .= "<a href='".$reset_link."'>Click Here</a>";

        \Mail::to($request->email)->send(new Websitemail($subject,$message));
        return redirect()->back()->with('success', 'We have sent a link to your email. Please check your inbox.');
    }
    public function reset_password($token, $email) {
        $user = User::where('email', $email)->where('token', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Token or Email not correct.');
        }
        return view('user.reset-password', compact('token', 'email'));
    }
    public function reset_password_submit(Request $request, $token, $email) {
        $request->validate([
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ]);
        $user = User::where('email', $email)->where('token', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Token or Email not correct.');
        }
        $user->password = Hash::make($request->password);
        $user->token = '';
        $user->update();

        return redirect()->route('login')->with('success', 'Password reset successfully');
    }
}
