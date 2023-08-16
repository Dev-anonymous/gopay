<?php

namespace App\Http\Controllers;

use App\Models\DemandeTransfert;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarchandWebController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $solde = tot_solde_marchand($compte->id);
        $trans = Transaction::where('compte_id', $compte->id)->count();

        $idsol = $user->comptes()->first()->soldes()->pluck('id')->all();
        $transfert =  DemandeTransfert::whereIn('solde_id', $idsol)->count();

        return view('marchand.index', compact('solde', 'trans', 'transfert'));
    }

    public function transaction()
    {
        $req = app()->handle(Request::create(route('marchand.api.trans')));
        if ($req->status() != 200) {
            $trans = [];
        } else {
            $trans = json_decode($req->getContent())->data;
        }
        return view('marchand.transaction', compact('trans'));
    }

    public function cash_out()
    {
        $req = app()->handle(Request::create(route('marchand.api.demande_trans')));
        if ($req->status() != 200) {
            $trans = [];
        } else {
            $trans = json_decode($req->getContent())->data;
        }
        return view('marchand.cash_out', compact('trans'));
    }

    public function cash_in()
    {
        $req = app()->handle(Request::create(route('marchand.api.trans'), 'GET', ['source' => 'E-PAY']));
        if ($req->status() != 200) {
            $trans = [];
        } else {
            $trans = json_decode($req->getContent())->data;
        }
        return view('marchand.cash_in', compact('trans'));
    }

    public function compte()
    {

        return view('marchand.compte');
    }

    public function integration()
    {

        return view('marchand.integration');
    }

    public function lien_pay()
    {
        return view('marchand.lien_pay');
    }
}
