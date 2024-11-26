<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\User;
use App\Notifications\SendMoney;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayoutController extends Controller
{
    use ApiResponser;
    function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->user = $request->___user;
            return $next($request);
        });
    }

    function balance()
    {
        $solde = $this->user->comptes()->first()->soldes()->get();
        $tab = [];
        foreach ($solde as $e) {
            $tab[$e->devise->devise] = formatMontant($e->montant);
        }
        return $this->success('BALANCE', $tab);
    }
    function gettransfert()
    {
        $idsol = $this->user->comptes()->first()->soldes()->pluck('id')->all();

        $demande = DemandeTransfert::whereIn('solde_id', $idsol)->orderBy('id', 'DESC')->paginate();
        $tab = [];
        foreach ($demande->getCollection() as $e) {
            $o = (object)[];
            $o->id = $e->id;
            $o->trans_id = $e->trans_id;
            $o->montant = formatMontant($e->montant, $e->solde->devise->devise);
            $o->au_numero = $e->au_numero;
            $o->status = $e->status;
            $o->note_validation = $e->note_validation;
            $o->date = $e->date->format('d-m-Y H:i:s');
            $o->date_denvoi = $e->date_denvoi->format('d-m-Y H:i:s');
            $o->date_validation = $e->date_validation?->format('d-m-Y H:i:s');
            array_push($tab, $o);
        }

        $data = $demande->toArray();
        $data['data'] = $tab;

        return $this->success("DEMANDES DE TRANSFERT", $data);
    }
    function newtransfert()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'devise' => 'required|in:CDF,USD',
                'montant' => 'required|numeric|',
                'telephone' => 'required|array|',
                'telephone.*' => 'string|min:10|max:10',
                'date_denvoi' => 'sometimes|date|after:now'
            ]
        );

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['errors_msg' => $validator->errors()->all()]);
        }
        $devise = request()->devise;
        $montant = request()->montant;
        $telephone = request()->telephone;

        if ($devise == 'CDF' and $montant < 500) {
            return $this->error("Le montant minimum de transfert est de 500 CDF");
        } else {
            if ($montant < .5) {
                return $this->error("Le montant minimum de transfert est de .5 USD");
            }
        }

        $err = [];
        foreach ($telephone as $tel) {
            $valide = true;

            if (!isvalidenumber($tel)) {
                $valide = false;
            }
            if (!is_numeric(substr($tel, 1))) {
                $valide = false;
            }
            if (!$valide) {
                $err[] = "Le numéro $tel n'est pas valide";
            }
        }

        if (count($err)) {
            return $this->error('Erreur de validation', ['errors_msg' => $err]);
        }


        $dev = Devise::where('devise', $devise)->first();
        $compte = $this->user->comptes()->first();

        $solde = $compte->soldes()->where(['devise_id' => $dev->id])->first();
        $montant_solde = $solde->montant;

        $dateenv = request('date_denvoi') ?? now('Africa/Lubumbashi');
        DB::beginTransaction();
        foreach ($telephone as $tel) {
            DemandeTransfert::create([
                'solde_id' => $solde->id,
                'au_numero' => $tel,
                'montant' => $montant,
                'date' => now('Africa/Lubumbashi'),
                'date_denvoi' => $dateenv,
                'trans_id' => trans_id('CASH.OUT', $this->user)
            ]);
        }
        $admin = User::where('user_role', 'admin')->first();
        try {
            $c = commission($this->user) * 100;
            $mo = formatMontant($montant, $devise);
            $mt = formatMontant($montant * count($telephone), $devise);
            $so = formatMontant($montant_solde, $devise);
            $tel = implode(' , ', $telephone);
            $m = "Demande de transfert de {$this->user->business_name}, {$this->user->name} </br>Montant total : $mt </br>Montant de transfert : $mo au $tel </br> Solde : $so </br> Commission: $c %, date de transfert $dateenv";
            $admin->notify(new SendMoney($m));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error("Un petit problème est survenu, veuillez réessayer SVP.");
        }

        return $this->success("Votre transfert sera traité à la date : $dateenv. Merci.");
    }

    function statustransfert($trans_id)
    {
        $compte = $this->user->comptes()->first();
        $soldeId = $compte->soldes()->pluck('id')->all();
        $trans = DemandeTransfert::where('trans_id', $trans_id)->whereIn('solde_id', $soldeId)->first();

        if (!$trans) {
            return $this->error("Transaction non trouvée pour $trans_id");
        }

        $o = (object)[];
        $o->id = $trans->id;
        $o->trans_id = $trans->trans_id;
        $o->montant = formatMontant($trans->montant, $trans->solde->devise->devise);
        $o->au_numero = $trans->au_numero;
        $o->status = $trans->status;
        $o->note_validation = $trans->note_validation;
        $o->date = $trans->date->format('d-m-Y H:i:s');
        $o->date_denvoi = $trans->date_denvoi->format('d-m-Y H:i:s');
        $o->date_validation = $trans->date_validation?->format('d-m-Y H:i:s');

        return  $this->success("Transaction $trans_id", $o);
    }
    function deltransfert($trans_id)
    {
        $compte = $this->user->comptes()->first();
        $soldeId = $compte->soldes()->pluck('id')->all();
        $trans = DemandeTransfert::where('trans_id', $trans_id)->whereIn('solde_id', $soldeId)->first();

        if (!$trans) {
            return $this->error("Transaction non trouvée pour $trans_id");
        }
        if ($trans->status != 'EN ATTENTE') {
            return $this->error("La transaction $trans_id  ne peut être supprimée. Seules les transactions 'EN ATTENTE' peuvent être supprimées.");
        }
        $trans->delete();
        return  $this->success("La transaction $trans_id a été supprimée.");
    }
}
