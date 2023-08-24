<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\Fp;
use App\Models\LienPaie;
use App\Models\Operateur;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Request;
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
                'telephone' => ['required', 'regex:/(\+24390|\+24399|\+24397|\+24398|\+24380|\+24381|\+24382|\+24383|\+24384|\+24385|\+24389)[0-9]{7}/']
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $_source = request()->_source;

        if (!in_array($_source, ['E-PAY', 'PAY-LINK'])) {
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

    public function accept_pay($id = null)
    {

        $id = (int) encode($id, false);
        $link = LienPaie::where('id', $id)->first();
        $valide = false;
        $user = $link?->compte->user;
        if ($link) {
            $valide = true;
        }
        return view('paiement', compact('link', 'valide', 'user'));
    }

    public function web_pay_init()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'link' => 'required|exists:lien_paie,id',
                'devise' => 'required|in:CDF,USD',
                'amount' => 'required|integer|',
                'phone' => ['required', 'regex:/(\+24390|\+24399|\+24397|\+24398|\+24380|\+24381|\+24382|\+24383|\+24384|\+24385|\+24389)[0-9]{7}/'],
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }
        $devise = request()->devise;
        $montant = request()->amount;
        $phone = request()->phone;

        if ($devise == 'CDF' and $montant < 500) {
            return $this->error("Le montant minimum de paiement est de 500 CDF");
        } else {
            if ($montant < 1) {
                return $this->error("Le montant minimum de paiement est de 1 USD");
            }
        }

        $link = LienPaie::where('id', request()->link)->first();
        if ($link->montant_fixe == 1 and $montant != $link->montant) {
            $err = "[" . now('Africa/Lubumbashi') . "] [LINK : $link->id] [" . json_encode(request()->all()) . "] [" . request()->userAgent() . "]\n";
            $file = fopen('LOG_INVALIDE_PAY_AMOUNT', 'a+');
            fwrite($file, $err);
            fclose($file);
            return $this->error("Le montant de paiement est de $link->montant $link->devise");
        }
        /** @var \App\Models\User $user **/
        $user = $link->compte->user;
        $key = $user->apikeys()->where('type', 'production')->first()->key;

        $params = [
            '_source' => 'PAY-LINK',
            'devise' => $devise,
            'amount' => $montant,
            'telephone' => $phone
        ];

        $request = Request::create(route('pay.init'), 'POST', $params);
        $request->headers->set('x-api-key', $key);
        $req = app()->handle($request);
        if ($req->status() != 200) {
            return $this->error("Une erreur s'est produite, veuillez réessayer.");
        } else {
            $data = json_decode($req->getContent());
        }

        return response()->json((array) $data);
    }

    public function web_pay_check()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'link' => 'required|exists:lien_paie,id',
                'ref' => 'required|exists:fp',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $ref = request()->ref;
        $link = LienPaie::where('id', request()->link)->first();

        /** @var \App\Models\User $user **/
        $user = $link->compte->user;
        $key = $user->apikeys()->where('type', 'production')->first()->key;
        $request = Request::create(route('pay.check', $ref), 'POST');
        $request->headers->set('x-api-key', $key);
        $req = app()->handle($request);
        if ($req->status() != 200) {
            return $this->error("Une erreur s'est produite, veuillez réessayer.");
        } else {
            $data = json_decode($req->getContent());
        }

        return response()->json((array) $data);
    }
}
