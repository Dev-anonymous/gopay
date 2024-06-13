<?php

namespace App\Http\Controllers;

use App\Mail\RecoveryMail;
use App\Models\Recovery;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    use ApiResponser;
    public function index()
    {
        return view('landing');
    }

    public function login()
    {
        if (Auth::check()) {
            $role = auth()->user()->user_role;
            $r = request()->r;
            if ($r) {
                $r = urldecode($r);
            }
            if ($role == 'admin') {
                return redirect($r ?? route('admin.web.index'));
            } else if ($role == 'marchand') {
                return redirect($r ?? route('marchand.web.index'));
            }
        }
        return view('login');
    }

    public function recoveryview()
    {
        if (Auth::check()) {
            $role = auth()->user()->user_role;
            $r = request()->r;
            if ($r) {
                $r = urldecode($r);
            }
            if ($role == 'admin') {
                return redirect($r ?? route('admin.web.index'));
            } else if ($role == 'marchand') {
                return redirect($r ?? route('marchand.web.index'));
            }
        }

        $token = request()->token;
        $show = !empty($token);

        $msg = '';
        $error = false;
        if ($show) {
            $min = strtotime(' - 1 day');
            $max = strtotime(' + 1 day');
            $rec = Recovery::where(['code' => $token])->where('date', '>=', $min)->where('date', '<=', $max)->first();
            if (!$rec) {
                $msg = "Cette url est invalide ou a déjà expirée! veuiller recommencer le processus de réinitialisation du mot de passe.";
                $error = true;
            }
        }
        return view("recovery", compact('show', 'msg', 'error'));
    }

    public function recovery()
    {
        $validator = Validator::make(request()->all(), [
            'login' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Erreur de validation', 400, ['errors_msg' => $validator->errors()->all()]);
        }

        $find = false;
        $data = $validator->validate();
        $login = $data['login'];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
            if ($user) {
                $find = true;
            }
        } else if (is_numeric($login)) {
            $login = "+" . (int) $login;
            $user = User::where('phone', "$login")->first();
            if ($user) {
                $find = true;
            }
        } else {
            return $this->error('Veuillez entrer votre numéro de téléphone ou votre email.', 200);
        }

        if (!$find) {
            return $this->error("Aucun compte trouvé pour \"$login\", veuillez ressayer", 200);
        }

        Recovery::where('date', '<', strtotime(' - 1 day'))->delete();

        $rec = $user->recoveries()->first();
        if (!$rec) {
            $rec = Recovery::create(['users_id' => $user->id, 'code' => code(20), 'date' => time()]);
        }

        if ($rec->tentative >= 5) {
            $d = date('d-m-Y H:i:s', $rec->date + 3600 * 24);
            return $this->error("Vous avez effectué 5 tentatives de réinitialisation, pour des raisons de sécurité, veuillez réessayer dans 24h ($d)", 200);
        }

        $email = $user->email;
        $phone = $user->phone;
        if (empty($email)) {
            return $this->error("Aucun email trouvé dans votre compte, veuillez contacter le service client pour réinitialiser votre compte.", 200);
        }

        try {
            $data['msg']  = "Cher(e) $user->name, cliquez sur ce lien pour réinitialiser votre mot de passe.";
            $data['url']  = route('recoveryview', ['token' => $rec->code]);
            Mail::to($email)->send(new RecoveryMail((object) $data));
            $rec->increment('tentative');
            $rec->update(['date' => time()]);
            return $this->success("Veuillez cliquer sur le lien que nous venons d'envoyé  à votre adresse mail.");
        } catch (\Throwable $th) {
            return $this->error("Oops! une petite erreur s'est produite, veuillez réessayer SVP.", 200);
        }
    }

    public function complete()
    {
        $validator = Validator::make(request()->all(), [
            'pass' => 'required|string|min:6|same:cpass',
            'cpass' => 'required|string|min:6|',
            'token' => 'required',
        ], [
            'pass.same' => "Les deux mot de passe sont differents",
            'pass.min' => "Le mdp doit avoir minimun 6 caractères",
            'cpass.min' => "",
        ]);

        if ($validator->fails()) {
            return $this->error(implode(',', $validator->errors()->all()));
        }

        $data = $validator->validated();

        $min = strtotime(' - 1 day');
        $max = strtotime(' + 1 day');
        $rec = Recovery::where(['code' => $data['token']])->where('date', '>=', $min)->where('date', '<=', $max)->first();
        if (!$rec) {
            $msg = "Votre token est invalide ou a déjà expiré! veuiller recommencer le processus de réinitialisation du mot de passe.";
            return $this->error($msg, 200);
        }
        $user =  $rec->user()->first();

        DB::beginTransaction();
        $user->update(['password' => Hash::make($data['pass'])]);
        $rec->delete();
        DB::commit();
        Auth::login($user);
        return $this->success("Votre mot de passe a été modifier avec succès.", [
            'token' => $user->createToken('token_' . time())->plainTextToken,
        ]);
    }
}
