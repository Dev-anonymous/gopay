<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {

        if (!User::first()) {
            User::create(['name' => 'Admin', 'email' => 'admin@admin.admin', 'password' => Hash::make('admin1001'), 'user_role' => 'admin']);
        }

        $attr = $request->all();
        $validator = Validator::make($attr, [
            'login' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $success = false;
        $data = $validator->validate();
        $login = $data['login'];
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $_ = ['password' => $data['password'], 'email' => $login];
            if (Auth::attempt($_, request('remember') ? true : false)) {
                $success = true;
            }
        } else if (is_numeric($login)) {
            $login = (float) $login;
            if ("243" != substr($login . 0, 3)) {
                $login = "243$login";
            }
            $login = "+" . $login;
            $_ = ['password' => $data['password'], 'phone' => $login];
            if (Auth::attempt($_, request('remember') ? true : false)) {
                $success = true;
            }
        } else {
            return $this->error('Validation error.', ['errors_msg' => ["You must provide your email or phone number to login"]]);
        }

        if (!$success) {
            return $this->error('Login error', ['errors_msg' => ["Invalid credentials"]]);
        }

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $user->update(['derniere_connexion' => now('Africa/Lubumbashi')]);

        return $this->success("You are now connected.", [
            'token' => $user->createToken('token_' . time())->plainTextToken,
            'role' => $user->user_role,
            'user' => $user
        ]);
    }

    public function logout(Request $r)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            Auth::guard('web')->logout();
        }
        return redirect(route('app.login'));
    }
}
