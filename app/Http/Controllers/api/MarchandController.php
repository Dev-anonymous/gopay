<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class MarchandController extends Controller
{
    use ApiResponser;

    public function solde($devise = null)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $solde = $user->comptes()->first()->soldes()->get();

        $tab = [];
        foreach ($solde as $e) {
            array_push($tab, formatMontant($e->montant, $e->devise->devise));
        }

        $devise = strtoupper($devise);
        if ($devise and !in_array($devise, ['USD', 'CDF'])) {
            return  $this->error("Devise non valide : $devise", []);
        }

        $r = $tab;
        if ($devise) {
            foreach ($r as $sol) {
                $dev = explode(' ', $sol)[1];
                if ($dev == $devise) {
                    $s[] = $sol;
                    $r = $s;
                    break;
                }
            }
        }
        return $this->success('Votre solde', $r);
    }
    public function transfert()
    {
        // /** @var \App\Models\User $user **/
        // $user = auth()->user();

        // $validator = Validator::make(
        //     request()->all(),
        //     [
        //         'numero_compte' => 'required|exists:compte,numero_compte',
        //         'devise_id' => 'required|exists:devise,id',
        //         'montant' => 'required|numeric|min:1',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        // }

        // $data = $validator->validated();
        // $cmpt = $data['numero_compte'];
        // $mont = $data['montant'];

        // $compte = $user->comptes()->first();
        // if ($cmpt == $compte->numero_compte) {
        //     return $this->error('Echec de transaction', ['errors_msg' => ["Numéro de compte non autorisé."]]);
        // }

        // $solde = $compte->soldes()->where(['devise_id' => $data['devise_id']])->first();
        // $montant_solde = $solde->montant;

        // if ($montant_solde < $mont) {
        //     return $this->error('Echec de transaction', ['errors_msg' => ["Vous disposez de $montant_solde {$solde->devise->devise} dans votre compte, votre transaction de $mont {$solde->devise->devise} ne peut etre effectuée, merci de recharger votre compte."]]);
        // }

        // DB::beginTransaction();
        // $solde->decrement('montant', $mont);

        // $d['compte_id'] = $compte->id;
        // $d['devise_id'] = $data['devise_id'];
        // $d['montant'] = $data['montant'];
        // $d['trans_id'] = $transid = trans_id();
        // $d['type'] = 'transfert';
        // $d['data'] = json_encode([
        //     'to' => $data['numero_compte']
        // ]);
        // Transaction::create($d);

        // $comptBenficiaire = Compte::where('numero_compte', $data['numero_compte'])->first();
        // $solde2 = $comptBenficiaire->soldes()->where(['devise_id' => $data['devise_id']])->first();
        // $solde2->increment('montant', $mont);

        // $d['compte_id'] = $comptBenficiaire->id;
        // $d['source'] = 'client';
        // $d['data'] = json_encode([
        //     'from' => $compte->numero_compte
        // ]);
        // Transaction::create($d);
        // DB::commit();

        // $msg = "Vous venez d'effectuer un tranfert de $mont {$solde->devise->devise} vers le compte {$comptBenficiaire->numero_compte}({$comptBenficiaire->user->name}). TransID : $transid";
        // return $this->success($msg);
    }


    public function transaction($limte = null)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $trans = Transaction::where('compte_id', $compte->id);
        $limte = (int) $limte;
        if ($limte) {
            $trans = $trans->limit($limte);
        }

        $trans = $trans->orderBy('id', 'desc')->get();

        $tab = [];

        foreach ($trans as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->trans_id = $e->trans_id;
            $a->montant = formatMontant($e->montant, $e->devise->devise);
            $a->type = $e->type;
            $a->source = $e->source;
            $op =  $e->operateur;
            if ($op) {
                $op = ['operateur' => $op->operateur, 'image' => asset('storage/' . $op->image)];
            }
            $a->operateur = $op;
            $a->date = $e->date->format('d-m-Y H:i:s');
            array_push($tab, $a);
        }

        $m = "Vos transactions";
        return $this->success($m, $tab);
    }

    public function demande_tranfert()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'devise' => 'required|in:CDF,USD',
                'montant' => 'required|integer|',
                'telephone' => 'required|min:1|regex:/(\+243)[0-9]{9}/',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }
        $devise = request()->devise;
        $montant = request()->montant;
        $telephone = request()->telephone;
        if ($devise == 'CDF' and $montant < 10000) {
            return $this->error("Le montant minimum de transfert est de 10 000 CDF");
        } else {
            if ($montant < 5) {
                return $this->error("Le montant minimum de transfert est de 5 USD");
            }
        }

        // $tel = (int) $telephone;
        // $orang = substr($tel, 0, 5);
        // if (!in_array($orang, ["24384", "24385", "24389", "24380"])) {
        //     return $this->error("Veuillez renseigner un numéro Orange SVP.");
        // }

        $dev = Devise::where('devise', $devise)->first();

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();

        $solde = $compte->soldes()->where(['devise_id' => $dev->id])->first();
        $montant_solde = $solde->montant;

        if ($montant_solde < $montant) {
            return $this->error("Vous disposez de $montant_solde {$solde->devise->devise} dans votre compte, votre demande de transfert de $montant {$solde->devise->devise} ne peut etre enregistrée pour le moment.", 200);
        }
        DemandeTransfert::create([
            'solde_id' => $solde->id,
            'au_numero' => $telephone, 'montant' => $montant,
            'date' => now('Africa/Lubumbashi'),
            'trans_id' => trans_id('CASH.OUT', $user)
        ]);
        return $this->success("Votre demande de transfert a été enregistrée et sera traité dans 24h. Merci.");
    }

    public function get_demande_tranfert()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $idsol = $user->comptes()->first()->soldes()->pluck('id')->all();
        $demande = DemandeTransfert::whereIn('solde_id', $idsol)->orderBy('id', 'DESC')->get();
        $tab = [];
        foreach ($demande as $e) {
            $o = (object)[];
            $o->id = $e->id;
            $o->trans_id = $e->trans_id;
            $o->montant = formatMontant($e->montant, $e->solde->devise->devise);
            $o->au_numero = $e->au_numero;
            $o->status = $e->status;
            $o->note_validation = $e->note_validation;
            $o->date = $e->date->format('d-m-Y H:i:s');
            $o->date_validation = $e->date_validation?->format('d-m-Y H:i:s');
            array_push($tab, $o);
        }
        return $this->success("Vos demandes de tranfert", $tab);
    }

    public function numero_compte()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $n = $compte->numero_compte;
        return $this->success("Mon numero de compte", $n);
    }
}
