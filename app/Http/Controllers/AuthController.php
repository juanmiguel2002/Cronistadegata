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
            'pageTitle' => 'Entrar'
        ];
        return view('back.pages.auth.login', $data);
    }

    public function forgotForm(Request $request){
        $data = [
            'pageTitle' => 'Olvidaste tu contraseña',
        ];
        return view('back.pages.auth.forgot', $data);
    }

    public function loginHandler(Request $request)
    {
        // Determinar si es login por email o username
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Validar campos
        $request->validate(
            [
                'login_id' => 'required',
                'password' => 'required|min:5',
                'remember' => 'sometimes|boolean',
            ],
            [
                'login_id.required' => 'Enter your email or username.',
                'password.required' => 'Password is required.',
            ]
        );

        // Además, validación condicional según tipo de login
        $this->validateUserExists($fieldType, $request->login_id);

        // Preparar credenciales
        $creds = [
            $fieldType => $request->login_id,
            'password' => $request->password,
        ];
        $remember = $request->boolean('remember'); // true si checkbox está marcado

        // Intentar autenticar con remember
        if (Auth::attempt($creds, $remember)) {
            // Revisar status del usuario
            if (in_array(Auth::user()->status, [UserStatus::Inactive, UserStatus::Pending])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $msg = Auth::user()->status === UserStatus::Inactive
                    ? 'Your account is inactive.'
                    : 'Your account is Pending.';

                return redirect()->route('admin.login')
                    ->withInput()
                    ->with('fail', $msg);
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')
            ->withInput()
            ->with('fail', 'Incorrect password.');
    }

    /**
     * Valida que el usuario exista por el tipo de campo.
     */
    protected function validateUserExists(string $fieldType, string $value): void
    {
        if ($fieldType === 'email') {
            request()->validate(
                ['login_id' => 'exists:users,email'],
                ['login_id.exists' => 'No account found for this email address.']
            );
        } else {
            request()->validate(
                ['login_id' => 'exists:users,username'],
                ['login_id.exists' => 'No account found for this username.']
            );
        }
    }

    public function sendPassword(Request $request){

        $request->validate([
            'email' =>'required|email|exists:users,email',
        ],[
            'email.required' => 'Por favor, introduzca su correo electrónico.',
            'email.email' => 'Dirección de correo electrónico no válida.',
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
