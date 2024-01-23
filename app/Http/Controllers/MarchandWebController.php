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
        return view('marchand.transaction');
    }

    public function cash_out()
    {
        $frais = commission() * 100;
        return view('marchand.cash_out', compact('frais'));
    }

    public function cash_in()
    {
        return view('marchand.cash_in');
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
