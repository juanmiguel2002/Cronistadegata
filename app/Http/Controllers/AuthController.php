<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm(Request $request){
        $data = [
            'pageTitle' => 'Login'
        ];
        return view('back.pages.auth.login', $data);
    }

    public function forgotForm(Request $request){
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        return view('back.pages.auth.forgot', $data);
    }

    public function loginHandler(Request $request){
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5'
            ],[
                'login_id.required' => 'Enter your email or username.',
                'login_id.email' => 'Invalid email address.',
                'login_id.exists' => 'No se encuentra el email.',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|email|exists:users,username',
                'password' => 'required|min:5'
            ],[
                'login_id.required' => 'Enter your email or username.',
                'login_id.exists' => 'No account found for this username.'
            ]);
        }
        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password
        );

        if (Auth::attempt($creds)) {
            if (Auth::user()->status == UserStatus::Inactive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->withInput()->with('fail', 'Your account is inactive.');
            } else if (Auth::user()->status == UserStatus::Pending) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->withInput()->with('fail', 'Your account is Pending.');
            }
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->withInput()->with('fail', 'Incorrect password.');

        }
    }
    public function sendPassword(Request $request){

        $request->validate([
            'email' =>'required|email|exists:users,email',
        ],[
            'email.required' => 'Please enter your email.',
            'email.email' => 'Invalid email address.',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = base64_decode(Str::random(64));

        $oldToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)->first();

        if ($oldToken) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)->update([
                    'token' => $token,
                    'created_at' => now()
                ]);
        }else {
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now()
            ]);
        }
        $link = route('admin.reset', $token);
        $user->sendPasswordResetNotification($user->createToken());
        return redirect()->route('admin.forgot')->with('success', 'Password reset link sent to your email.');
    }
}
