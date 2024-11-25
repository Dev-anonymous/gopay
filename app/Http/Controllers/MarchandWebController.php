<?php

namespace App\Http\Controllers;

use App\Models\DemandeTransfert;
use App\Models\Taux;
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
        $years = Transaction::where('compte_id', $compte->id)->orderBy('date', 'desc')->selectRaw('year(date) as year')->pluck('year')->all();
        $years = array_unique($years);
        $taux = Taux::first();

        return view('marchand.index', compact('years', 'taux'));
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

    public function users()
    {
        return view('marchand.users');
    }

    public function payout()
    {
        return view('marchand.payout');
    }
}
