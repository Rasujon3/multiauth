<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Admin;
use App\Mail\Websitemail;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }
    public function login() {
        return view('admin.login');
    }
    public function login_submit(Request $request) {
        $request->validate([
           'email' => ['required', 'email'],
           'password' => ['required']
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password']
        ];
        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin_dashboard');
        }
        return redirect()->route('admin_login')->with('error', 'Invalid Credentials');
    }
    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login')->with('success', 'Logged out successfully');
    }
    public function forget_password() {
        return view('admin.forget-password');
    }
    public function forget_password_submit(Request $request) {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return redirect()->back()->with('error', 'Email not found.');
        }
        $token = hash('sha256', time());
        $admin->token = $token;
        $admin->update();
        $reset_link = url('/admin/reset-password/'.$token.'/'.$request->email);
        $subject = 'Reset Password';
        $message = "Click the link below to reset your password.<br>";
        $message .= "<a href='".$reset_link."'>Click Here</a>";
        \Mail::to($request->email)->send(new Websitemail($subject,$message));
        return redirect()->back()->with('success', 'We have sent a link to your email. Please check your inbox.');
    }
    public function reset_password($token, $email) {
        $admin = Admin::where('email', $email)->where('token', $token)->first();
        if (!$admin) {
            return redirect()->route('admin_login')->with('error', 'Token or Email not correct.');
        }
        return view('admin.reset-password', compact('token', 'email'));
    }
    public function reset_password_submit(Request $request, $token, $email) {
        $request->validate([
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ]);
        $admin = Admin::where('email', $request->email)->where('token', $request->token)->first();
        if (!$admin) {
            return redirect()->route('admin_login')->with('error', 'Token or Email not correct.');
        }
        $admin->password = Hash::make($request->password);
        $admin->token = '';
        $admin->update();

        return redirect()->route('admin_login')->with('success', 'Password reset successfully');
    }
}
