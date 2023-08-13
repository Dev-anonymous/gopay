<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
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
}
