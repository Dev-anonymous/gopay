<?php

use App\Models\Apikey;
use App\Models\Compte;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Fp;
use App\Models\Solde;
use App\Models\SoldeApp;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

define('FLEXPAY_HEADERS', [
    "Content-Type: application/json",
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJcL2xvZ2luIiwicm9sZXMiOlsiTUVSQ0hBTlQiXSwiZXhwIjoxNzI2NzM2NzQ4LCJzdWIiOiI2ZGQzMWVmOTNkNzQ2ZmQ2NmU5ZjZjZDRhMWNjM2M2YiJ9.A5wcsvDM1wi_xdsWJQOM18IZaBPvyTPRAQFgvi0WIlg"
]);
define('MARCHAND', 'GROUPER');
define('API_BASE', 'https://backend.flexpay.cd/api/rest/v1');
define('COMMISSION', 0.03);

// function getMimeType($filename)
// {
//     if (!file_exists($filename)) return '';
//     $mimetype = mime_content_type($filename);
//     if (strpos($mimetype, 'image') !== false) {
//         $mimetype = 'image';
//     } else if (strpos($mimetype, 'audio') !== false) {
//         $mimetype = 'audio';
//     } else if (strpos($mimetype, 'video') !== false) {
//         $mimetype = 'video';
//     }
//     return $mimetype;
// }

function formatMontant($montant, $devise = '')
{
    return trim(number_format($montant, 2, '.', ' ') . " $devise");
}

function encode($str, $encrypt = true)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = '781227';
    $secret_iv = '2002';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($encrypt == true) {
        $output = openssl_encrypt($str, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else {
        $output = openssl_decrypt(base64_decode($str), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function getUser()
{
    $apikey = request()->header('x-api-key');
    if (!$apikey) {
        abort(403, "API Key is required");
    }
    $at = Apikey::where('key', $apikey)->first();
    if (!$at) {
        abort(403, "Invalid API Key");
    }
    return $at->user;
}

function numeroCompte()
{
    $compte = Compte::all();
    $n = $compte->count() + 1;

    if ($n < 10) {
        $c = "C00$n";
    } else if ($n >= 10 and $n < 100) {
        $c = "C0$n";
    } else {
        $c = "C$n";
    }
    $c = $c . '.' . makeRand() . '.' . makeRand();
    return $c;
}

function makeRand($max = 5)
{
    $max = (int) $max;
    if (!$max or $max <= 0) {
        return 0;
    }

    $num = '';
    while ($max > 0) {
        $max--;
        $num .= rand(1, 9);
    }
    return $num;
}

function zero($val, int $n = 4)
{
    if ($n <= 0) return $val;
    $vallen = strlen($val);
    if ($vallen == $n) return $val;
    $zero = $n - $vallen;
    if ($zero <= 0) return $val;

    $t = [];
    while ($zero) {
        $t[] = 0;
        $zero--;
    }
    return  implode($t) . $val;
}

function trans_id($type, $user)
{
    if ($type == 'CASH.IN') {
        $tr = Transaction::where('compte_id',  $user->comptes()->first()->id)->get();
    } else if ($type == 'CASH.OUT') {
        $tr = DemandeTransfert::whereIn('solde_id',  $user->comptes()->first()->soldes()->pluck('id')->all())->get();
    } else {
        die;
    }
    $n = $tr->count() + 1;

    $c = strtoupper("$type-") . zero($n, 4);
    $c = $c . '.' . makeRand() . '.' . makeRand();
    return $c;
}

function startFlexPay($devise, $montant, $telephone, $ref, $cb_code)
{
    $_api_headers = FLEXPAY_HEADERS;

    $telephone = (float) $telephone;
    $data = array(
        "merchant" => MARCHAND,
        "type" => "1",
        "phone" => "$telephone",
        "reference" => "$ref",
        "amount" => "$montant",
        "currency" => "$devise",
        "callbackUrl" => route('payment.callback.web', $cb_code),
    );


    $data = json_encode($data);
    $gateway = API_BASE . "/paymentService";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gateway);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $_api_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $response = curl_exec($ch);
    $rep['status'] = false;
    if (curl_errno($ch)) {
        $rep['message'] = "Erreur, veuillez reessayer.";
    } else {
        $jsonRes = json_decode($response);
        $code = $jsonRes->code ?? '';
        if ($code != "0") {
            $rep['message'] = "Erreur, veuillez reessayer : " . @$jsonRes->message;
            $rep['data'] = $jsonRes;
        } else {
            $rep['status'] = true;
            $rep['message'] = "Transaction initialisée avec succès. Veuillez saisir votre code Mobile Money pour confirmer la transaction.";
            $rep['data'] = $jsonRes;
        }
    }
    curl_close($ch);
    return $rep;
}

function completeFlexpayTrans()
{
    $pendingPayments = Fp::where(['callback' => '1', 'is_saved' => '0', 'transaction_was_failled' => '0'])->get();
    foreach ($pendingPayments as $e) {
        $paydata = json_decode($e->pay_data);
        $orderNumber = $paydata->apiresponse->orderNumber;
        $t = transaction_was_success($orderNumber);
        if ($t === true) {
            saveData($paydata, $e);
        } else {
            if ($t === false) {
                $e->update(['transaction_was_failled' => 1]);
            }
        }
    }
}

function transaction_was_success($orderNumber)
{
    $_api_headers = FLEXPAY_HEADERS;

    $gateway = API_BASE . "/check/" . $orderNumber;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gateway);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $_api_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $response = curl_exec($ch);
    $status = null;
    if (!curl_errno($ch)) {
        curl_close($ch);
        $jsonRes = json_decode($response);
        $code = $jsonRes->code ?? '';
        if ($code == "0") {
            if ($jsonRes->transaction->status == '0') {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }
    }
    return $status;
}


function saveData($payedata, $e)
{
    $user = User::where('id', json_decode($e->user)->id)->first();
    if ($user) {
        $compte = $user->comptes()->first();
        $trans_data = (array) $payedata->paydata->trans_data;
        DB::beginTransaction();
        Transaction::create($trans_data);
        $did = $trans_data['devise_id'];
        $mt = $trans_data['montant'];
        $solde = $compte->soldes()->where(['devise_id' => $did]);
        $solde->increment('montant', $mt);
        $dev = strtolower(Devise::where('id', $did)->first()->devise);
        $appsolde = SoldeApp::first();
        $inc = $mt * COMMISSION;

        if (!$appsolde) {
            SoldeApp::create(["solde_$dev" => $inc]);
        } else {
            SoldeApp::first()->increment("solde_$dev", $inc);
        }
        $e->update(['is_saved' => 1]);
        DB::commit();
    }
}

function total_transaction()
{
    $tot = Transaction::selectRaw('*,sum(montant) as montant')->groupBy('devise_id')->get()->pluck('montant', 'devise.devise')->all();
    $t = [];
    foreach ($tot as $k => $v) {
        $t[$k] = $v;
    }
    @$t['CDF'] ?: ($t['CDF'] = 0.0);
    @$t['USD'] ?: ($t['USD'] = 0.0);
    return order_dev($t);
}

function total_cashout()
{
    $tot = DB::select("SELECT sum(demande_transfert.montant) as montant, devise FROM solde JOIN devise ON devise.id=solde.devise_id JOIN demande_transfert ON demande_transfert.solde_id=solde.id WHERE demande_transfert.status='TRAITÉE' GROUP BY solde.devise_id");
    $t = [];
    foreach ($tot as $e) {
        $t[$e->devise] = $e->montant;
    }
    @$t['CDF'] ?: ($t['CDF'] = 0.0);
    @$t['USD'] ?: ($t['USD'] = 0.0);
    return order_dev($t);
}

function tot_solde_marchand($idcompte = null, $commission = false)
{
    $tot = Solde::selectRaw('*,sum(montant) as montant')->groupBy('devise_id');
    if ($idcompte) {
        $tot = $tot->where('compte_id', $idcompte);
    }

    $tot = $tot->get()->pluck('montant', 'devise.devise')->all();
    $t = [];
    foreach ($tot as $k => $v) {
        $t[$k] = $v;
    }
    @$t['CDF'] ?: ($t['CDF'] = 0.0);
    @$t['USD'] ?: ($t['USD'] = 0.0);

    if ($commission) {
        $t['CDF'] -= $t['CDF'] * COMMISSION;
        $t['USD'] -= $t['USD'] * COMMISSION;
    }

    return order_dev($t);
}

function solde()
{
    $soldepp = SoldeApp::first();

    $CDF = (float) @$soldepp->solde_cdf;
    $USD = (float) @$soldepp->solde_usd;
    $t = compact('CDF', 'USD');
    return order_dev($t);
}

function order_dev($tab)
{
    $u = $tab['USD'];
    unset($tab['USD']);
    $tab['USD'] = $u;
    return $tab;
}

function all_trans()
{
    $tab['solde'] = solde();
    $tab['cashout'] = total_cashout();
    $tab['trans'] = total_transaction();
    $tab['solde_marchand'] = tot_solde_marchand(null, true);

    $tab['nb_trans'] = Transaction::count();
    return (object) $tab;
}
