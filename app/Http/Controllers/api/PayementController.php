<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Fp;
use App\Models\Operateur;
use App\Models\Transaction;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use stdClass;

class PayementController extends Controller
{
    use ApiResponser;

    public function payCallBack($cb_code = null)
    {
        if ($cb_code) {
            Fp::where(['is_saved' => 0, 'cb_code' => $cb_code])->update(['callback' => 1]);
        }
    }

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
            return  $this->error("Devise non valide : $devise", 400, []);
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

    public function payinit()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'devise' => 'required|in:CDF,USD',
                'montant' => 'required|numeric|',
                'telephone' => 'required|min:1|regex:/(243)[0-9]{9}/',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }

        $dev = request()->devise;
        $montant = request()->montant;
        $telephone = request()->telephone;

        if ($dev == 'CDF' && $montant < 500) {
            return $this->error('Validation error', 400, ['errors_msg' => ["Le montant minimum de paiement est de 500 $dev"]]);
        }

        $user = getUser();
        $compte = $user->comptes()->first();

        $ref = strtoupper(uniqid('REF-', true));
        $cb_code = time() . rand(20000, 90000);

        $_paydata = [
            'devise' => $dev,
            'montant' => $montant,
            'telephone' => $telephone,
            'trans_data' => [
                'compte_id' => $compte->id,
                'devise_id' => Devise::where('devise', $dev)->first()->id,
                'montant' => $montant,
                'trans_id' => trans_id(),
                'date' => now('Africa/Lubumbashi')
            ]
        ];

        $rep = startFlexPay($dev, $montant, $telephone, $ref, $cb_code);
        if ($rep['status'] == true) {
            $paydata = [
                'paydata' => $_paydata,
                'apiresponse' => $rep['data']
            ];
            Fp::create([
                'user' => $user,
                'cb_code' => $cb_code,
                'ref' => $ref,
                'pay_data' => json_encode($paydata),
            ]);
            return $this->success($rep['message'], ['ref' => $ref]);
        } else {
            return $this->error($rep['message'], 200);
        }
    }

    public function paycheck($ref = null)
    {
        if (!$ref) {
            return $this->error('Ref ?', 400);
        }
        $ok =  false;
        $flex = Fp::where(['ref' => $ref])->first();

        if ($flex) {
            $orderNumber = @json_decode($flex->pay_data)->apiresponse->orderNumber;
            if ($orderNumber) {
                $success = transaction_was_success($orderNumber);
                if ($success) {
                    if ($flex->is_saved != 1) {
                        $paydata = json_decode($flex->pay_data);
                        saveData($paydata, $flex);
                        $ok =  true;
                    }
                }
            }
        }

        if ($ok || $flex->is_saved == 1) {
            return $this->success("Votre transaction est effectuée avec succès.");
        } else {
            $m = "Aucune transaction trouvée.";
            return $this->error($m, 200);
        }
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
        //     return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        // }

        // $data = $validator->validated();
        // $cmpt = $data['numero_compte'];
        // $mont = $data['montant'];

        // $compte = $user->comptes()->first();
        // if ($cmpt == $compte->numero_compte) {
        //     return $this->error('Echec de transaction', 400, ['errors_msg' => ["Numéro de compte non autorisé."]]);
        // }

        // $solde = $compte->soldes()->where(['devise_id' => $data['devise_id']])->first();
        // $montant_solde = $solde->montant;

        // if ($montant_solde < $mont) {
        //     return $this->error('Echec de transaction', 400, ['errors_msg' => ["Vous disposez de $montant_solde {$solde->devise->devise} dans votre compte, votre transaction de $mont {$solde->devise->devise} ne peut etre effectuée, merci de recharger votre compte."]]);
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
            $a->montant = "$e->montant {$e->devise->devise}";
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
                'montant' => 'required|numeric|',
                'telephone' => 'required|min:1|regex:/(\+243)[0-9]{9}/',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }
        $devise = request()->devise;
        $montant = request()->montant;
        $telephone = request()->telephone;
        $dev = Devise::where('devise', $devise)->first();

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();

        $solde = $compte->soldes()->where(['devise_id' => $dev->id])->first();
        $montant_solde = $solde->montant;

        if ($montant_solde < $montant) {
            return $this->error("Vous disposez de $montant_solde {$solde->devise->devise} dans votre compte, votre demande de transfert de $montant {$solde->devise->devise} ne peut etre enregistrée pour le moment.", 200);
        }
        DemandeTransfert::create(['solde_id' => $solde->id, 'au_numero' => $telephone, 'montant' => $montant, 'date' => now()]);
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
            $o->montant = formatMontant($e->montant, $e->solde->devise->devise);
            $o->au_numero = $e->au_numero;
            $o->status = $e->status;
            $o->note_validation = $e->note_validation;
            $o->date = $e->date->format('d-m-Y H:i:s');
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

    public function devise()
    {
        $dev = Devise::get(['id', 'devise']);
        return $this->success("Liste devises", $dev);
    }

    public function operateur()
    {
        $dev = Operateur::get(['id', 'operateur']);
        return $this->success("Liste operateurs", $dev);
    }
}
