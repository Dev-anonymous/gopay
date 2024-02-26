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
        $file = 'flexpaycallback.json';
        if (!file_exists($file)) {
            touch($file);
        }
        $d = file_get_contents($file);
        $data = (array) json_decode($d);

        $r = (object) [];
        $r->date = now('Africa/Lubumbashi')->format('d-m-Y H:i:s');
        $r->url = request()->url();
        $r->useragent = request()->userAgent();
        $r->cb_code = $cb_code;
        $r->getpost = request()->all();
        $r->method = request()->method();
        array_push($data, $r);
        file_put_contents($file, json_encode($data));

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
                'amount' => 'required|numeric|',
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
                'ref' => $ref,
                'source' => $_source,
                'compte_id' => $compte->id,
                'devise_id' => Devise::where('devise', $dev)->first()->id,
                'montant' => $montant,
                'trans_id' => trans_id('CASH.IN', $user),
                'date' => now('Africa/Lubumbashi')->format('Y-m-d H:i:s'),
                'data' => json_encode([
                    'telephone' => $telephone,
                    'ref' => $ref,
                ])
            ]
        ];

        $rep = startFlexPay($dev, $montant, $telephone, $ref, $cb_code);

        $paydata = [
            'paydata' => $_paydata,
        ];
        $fp = Fp::create([
            'user' => $user,
            'cb_code' => $cb_code,
            'ref' => $ref,
            'pay_data' => json_encode($paydata)
        ]);

        if ($rep['status'] == true) {
            $paydata['apiresponse'] = $rep['data'];
            $fp->update(['pay_data' => json_encode($paydata)]);
            return $this->success($rep['message'], ['ref' => $ref]);
        } else {
            return $this->error($rep['message']);
        }
    }

    public function paycheck($ref = null)
    {
        if (!$ref) {
            return $this->error('Ref ?');
        }
        $ok =  false;
        $is_saved = 0;
        $flex = Fp::where(['ref' => $ref])->lockForUpdate()->first();

        if (!$flex) {
            return response()->json([
                'success' => false,
                'message' => "Invalid reference number",
                'transaction' => null
            ]);
        }

        $pay_data = @json_decode($flex->pay_data);
        $orderNumber = @$pay_data->apiresponse->orderNumber;
        if ($orderNumber) {
            $t = transaction_was_success($orderNumber);
            if ($t === true) {
                $is_saved = @Fp::where(['ref' => $ref])->first()->is_saved;
                if ($is_saved !== 1) {
                    $paydata = json_decode($flex->pay_data);
                    saveData($paydata, $flex);
                    $ok =  true;
                    $flex->update(['transaction_was_failled' => 0]);
                }
            } else {
                if ($t === false) {
                    $flex->update(['transaction_was_failled' => 1]);
                }
            }
        }


        if ($ok || $is_saved === 1 || @$flex->is_saved === 1) {
            $data = [
                'status' => 'success',
                'amount' => $pay_data->paydata->montant,
                'currency' => $pay_data->paydata->devise,
                'trans_id' => $pay_data->paydata->trans_data->trans_id,
                'source' => $pay_data->paydata->trans_data->source,
                'date' => $pay_data->paydata->trans_data->date,
            ];
            return response()->json([
                'success' => true,
                'message' => "Votre transaction est effectuée avec succès.",
                'transaction' => $data
            ]);
        } else {
            $data = [
                'status' => @$t === false ? 'failed' : 'pending'
            ];
            return response()->json([
                'success' => false,
                'message' => "Aucune transaction trouvée.",
                'transaction' => $data
            ]);
        }
    }


    public function payinitV2()
    {
        $_source = request()->_source;
        $rules = [
            'myref' => 'required|unique:fp',
            'devise' => 'required|in:CDF,USD',
            'amount' => 'required|numeric|',
            'telephone' => ['required', 'regex:/(\+24390|\+24399|\+24397|\+24398|\+24380|\+24381|\+24382|\+24383|\+24384|\+24385|\+24389)[0-9]{7}/']
        ];

        if (!in_array($_source, ['E-PAY', 'PAY-LINK'])) {
            $_source = 'API';
        }

        $validator = Validator::make(request()->all(), $rules, ['myref.unique' => "Invalid reference (myref), please retry."]);

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $myref = request()->myref;
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
                'ref' => $ref,
                'source' => $_source,
                'compte_id' => $compte->id,
                'devise_id' => Devise::where('devise', $dev)->first()->id,
                'montant' => $montant,
                'trans_id' => trans_id('CASH.IN', $user),
                'date' => now('Africa/Lubumbashi')->format('Y-m-d H:i:s'),
                'data' => json_encode([
                    'telephone' => $telephone,
                    'ref' => $ref,
                    'myref' => $myref
                ])
            ]
        ];

        $paydata = [
            'paydata' => $_paydata,
        ];
        $fp = Fp::create([
            'user' => $user,
            'cb_code' => $cb_code,
            'myref' => $myref,
            'ref' => $ref,
            'pay_data' => json_encode($paydata)
        ]);

        $rep = startFlexPay($dev, $montant, $telephone, $ref, $cb_code);

        if ($rep['status'] == true) {
            $paydata['apiresponse'] = $rep['data'];
            $fp->update(['pay_data' => json_encode($paydata)]);
            return $this->success($rep['message'], ['ref' => $ref, 'myref' => $myref]);
        } else {
            return $this->error($rep['message']);
        }
    }

    public function paycheckV2($myref = null)
    {
        if (!$myref) {
            return $this->error('myref is missing');
        }

        $ok =  false;
        $is_saved = 0;
        $flex = Fp::where(['myref' => $myref])->lockForUpdate()->first();

        if (!$flex) {
            return response()->json([
                'success' => false,
                'message' => "Invalid reference number (myref)",
                'transaction' => null
            ]);
        }

        $pay_data = @json_decode($flex->pay_data);
        $orderNumber = @$pay_data->apiresponse->orderNumber;
        if ($orderNumber) {
            $t = transaction_was_success($orderNumber);
            if ($t === true) {
                $is_saved = @Fp::where(['myref' => $myref])->first()->is_saved;
                if ($is_saved !== 1) {
                    $paydata = json_decode($flex->pay_data);
                    saveData($paydata, $flex);
                    $ok =  true;
                    $flex->update(['transaction_was_failled' => 0]);
                }
            } else {
                if ($t === false) {
                    $flex->update(['transaction_was_failled' => 1]);
                }
            }
        }


        if ($ok || $is_saved === 1 || @$flex->is_saved === 1) {
            $data = [
                'status' => 'success',
                'amount' => $pay_data->paydata->montant,
                'currency' => $pay_data->paydata->devise,
                'trans_id' => $pay_data->paydata->trans_data->trans_id,
                'source' => $pay_data->paydata->trans_data->source,
                'date' => $pay_data->paydata->trans_data->date,
            ];
            return response()->json([
                'success' => true,
                'message' => "Votre transaction est effectuée avec succès.",
                'transaction' => $data
            ]);
        } else {
            $data = [
                'status' => @$t === false ? 'failed' : 'pending'
            ];
            return response()->json([
                'success' => false,
                'message' => "Aucune transaction trouvée.",
                'transaction' => $data
            ]);
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
                'amount' => 'required|numeric|',
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

        $myref = 'myref' . time() . rand(10000, 90000);

        $params = [
            '_source' => 'PAY-LINK',
            'devise' => $devise,
            'amount' => $montant,
            'telephone' => $phone,
            'myref' => $myref,
        ];

        $request = Request::create(route('pay.initV2'), 'POST', $params);
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
                'myref' => 'required|exists:fp',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $myref = request()->myref;
        $link = LienPaie::where('id', request()->link)->first();

        /** @var \App\Models\User $user **/
        $user = $link->compte->user;
        $key = $user->apikeys()->where('type', 'production')->first()->key;
        $request = Request::create(route('pay.checkV2', $myref));
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
