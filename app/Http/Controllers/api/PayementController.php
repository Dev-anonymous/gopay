<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Fp;
use App\Models\Operateur;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;

class PayementController extends Controller
{
    use ApiResponser;

    public function payCallBack($cb_code = null)
    {
        if ($cb_code) {
            Fp::where(['is_saved' => 0, 'cb_code' => $cb_code])->update(['callback' => 1]);
        }
    }

    public function payinit()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'devise' => 'required|in:CDF,USD',
                'amount' => 'required|integer|',
                'telephone' => 'required|min:1|regex:/(243)[0-9]{9}/',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $_source = request()->_source;

        if (!in_array($_source, ['E-PAY'])) {
            $_source = 'API';
        }
        $dev = request()->devise;
        $montant = request()->amount;
        $telephone = request()->telephone;

        if ($dev == 'CDF' and $montant < 500) {
            return $this->error("Le montant minimum de paiement est de 500 CDF");
        } else {
            if ($montant < 1) {
                return $this->error("Le montant minimum de paiement est de 1 USD");
            }
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
                'source' => $_source,
                'compte_id' => $compte->id,
                'devise_id' => Devise::where('devise', $dev)->first()->id,
                'montant' => $montant,
                'trans_id' => trans_id('CASH.IN', $user),
                'date' => now('Africa/Lubumbashi'),
                'data' => json_encode([
                    'telephone' => $telephone,
                    'ref' => $ref,
                ])
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
            return $this->error($rep['message']);
        }
    }

    public function paycheck($ref = null)
    {
        if (!$ref) {
            return $this->error('Ref ?', 400);
        }
        $ok =  false;
        $flex = Fp::where(['ref' => $ref, 'transaction_was_failled' => 0])->first();

        if ($flex) {
            $orderNumber = @json_decode($flex->pay_data)->apiresponse->orderNumber;
            if ($orderNumber) {
                $t = transaction_was_success($orderNumber);
                if ($t === true) {
                    if ($flex->is_saved != 1) {
                        $paydata = json_decode($flex->pay_data);
                        saveData($paydata, $flex);
                        $ok =  true;
                    }
                } else {
                    if ($t === false) {
                        $flex->update(['transaction_was_failled' => 1]);
                    }
                }
            }
        }

        if ($ok || @$flex->is_saved == 1) {
            return $this->success("Votre transaction est effectuée avec succès.");
        } else {
            $m = "Aucune transaction trouvée.";
            return $this->error($m, 200);
        }
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

    public function accept_pay($id = null){
        
    }
}
