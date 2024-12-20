<?php

use App\Models\Apikey;
use App\Models\Compte;
use App\Models\Config;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Fp;
use App\Models\Pendingmail;
use App\Models\Solde;
use App\Models\SoldeApp;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\SendMoney;
use Illuminate\Support\Facades\DB;

define('FLEXPAY_HEADERS', [
    "Content-Type: application/json",
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJcL2xvZ2luIiwicm9sZXMiOlsiTUVSQ0hBTlQiXSwiZXhwIjoxNzg5ODk4NjY2LCJzdWIiOiIwMzA3MmRkZDE5ZDM5ZDIzMmQyMDZjZmM0OWI2NmNlYyJ9.K3Z_lajU4fOwRQUxGWQyBUb_oHFHgRwshw5VOIy8O8I"
]);
define('MARCHAND', 'GROUPER');
define('API_BASE', 'https://backend.flexpay.cd/api/rest/v1');

define('TAUX_CHANGE', 1 / 100);


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
            $rep['message'] = "Transaction initialisée avec succès. Veuillez saisir votre Pin mobile Money pour confirmer la transaction.";
            $rep['data'] = $jsonRes;
        }
    }
    curl_close($ch);
    return $rep;
}

function completeFlexpayTrans()
{
    $pendingPayments = Fp::where(['is_saved' => '0', 'transaction_was_failled' => '0'])->get();
    foreach ($pendingPayments as $e) {
        $paydata = json_decode($e->pay_data);
        try {
            $orderNumber = $paydata->apiresponse->orderNumber;
            $t = transaction_was_success($orderNumber);
            if ($t === true) {
                saveData($paydata, $e);
            } else {
                if ($t === false) {
                    $e->update(['transaction_was_failled' => 1]);
                }
            }
        } catch (\Throwable $th) {
            # $paydata->apiresponse is null, flexpay init was not responded
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
            } else if ($jsonRes->transaction->status == '1') { // 2=> en attente
                $status = false;
            }
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
        DB::transaction(function () use ($compte, $trans_data, $e, $user) {
            $trans_data['date'] = now('Africa/Lubumbashi');
            Transaction::create($trans_data);
            $did = $trans_data['devise_id'];
            $mt = $trans_data['montant'];
            $solde = $compte->soldes()->where(['devise_id' => $did]);
            $solde->increment('montant', $mt);
            $devise = Devise::where('id', $did)->first();
            $dev = strtolower($devise->devise);
            $appsolde = SoldeApp::first();
            $inc = $mt * commission($user);

            if (!$appsolde) {
                SoldeApp::create(["solde_$dev" => $inc]);
            } else {
                SoldeApp::first()->increment("solde_$dev", $inc);
            }
            $e->update(['is_saved' => 1]);

            $autosend = getconfig('autosend', $user) == 'yes';
            if ($autosend) {
                $autosenddata = (array) json_decode(getconfig('autosenddata', $user));
                if (count($autosenddata)) {
                    $tab = [];
                    $source = $trans_data['source'];
                    foreach ($autosenddata as $as) {
                        if ($as->source == $source) {
                            $tab[] = $as;
                            break;
                        }
                    }
                    if (count($tab)) {
                        $tab = end($tab); // always 1
                        if (100 == array_sum($tab->percent)) {
                            $montant = $trans_data['montant'];

                            foreach ($tab->phone as $key => $val) {
                                $num = $val;
                                $perc = $tab->percent[$key];
                                $mont = $montant * ($perc / 100);

                                $compte = $user->comptes()->first();
                                $solde = $compte->soldes()->where(['devise_id' => $devise->id])->first();
                                $montant_solde = $solde->montant;

                                $comm = $montant * commission($user);
                                $m =  $montant + $comm;

                                DemandeTransfert::create([
                                    'solde_id' => $solde->id,
                                    'au_numero' => $num,
                                    'montant' => $mont,
                                    'date' => now('Africa/Lubumbashi'),
                                    'trans_id' => trans_id('CASH.OUT', $user)
                                ]);

                                $c = commission($user) * 100;
                                $mo = formatMontant($mont, $devise->devise);
                                $so = formatMontant($montant_solde, $devise->devise);
                                $da = now('Africa/Lubumbashi');
                                $m = "Demande de transfert de $user->business_name, $user->name </br>Montant : $mo ($perc% de $montant) au $num </br> Solde : $so </br> Commission: $c %, date $da";

                                Pendingmail::create([
                                    'subject' => 'Send Money [auto]',
                                    'to' => 'contact@gooomart.com',
                                    'text' => $m,
                                    'date' => $da,
                                ]);
                            }
                        }
                    }
                }
            }
        });
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

function tot_solde_marchand($idcompte = null)
{
    if ($idcompte) {
        $cdf = $usd = 0;
        $cmpt = Compte::find($idcompte);
        $solde = $cmpt->soldes()->selectRaw('*,sum(montant) as montant')->groupBy('devise_id')->get()->pluck('montant', 'devise.devise')->all();

        $cdf0 = $solde['CDF'];
        $usd0 = $solde['USD'];
        $com = $cmpt->user->commission;

        $cdf += $cdf0;
        $usd += $usd0;
    } else {
        $cdf = $usd = 0;
        foreach (Compte::all() as $el) {
            $solde = $el->soldes()->selectRaw('*,sum(montant) as montant')->groupBy('devise_id')->get()->pluck('montant', 'devise.devise')->all();
            $cdf0 = $solde['CDF'];
            $usd0 = $solde['USD'];
            $com = $el->user->commission;

            $cdf0 -= $cdf0 * $com;
            $usd0 -= $usd0 * $com;

            $cdf += $cdf0;
            $usd += $usd0;
        }
    }

    $t['CDF'] = $cdf;
    $t['USD'] = $usd;

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
    $tab['solde_marchand'] = tot_solde_marchand();

    $tab['nb_trans'] = Transaction::count();
    return (object) $tab;
}


function mask_num($num)
{
    $num = (int) $num;
    $num = substr($num, 3);
    $pref = substr($num, 0, 4);
    $suf = substr($num, -2);
    $mask = "0{$pref}xxx$suf";
    return $mask;
}

function makepay_link(int $id)
{
    $encode = encode($id);
    $url = route('payment.accept_pay.web', $encode);
    return $url;
}
function getpay_link($encoded)
{
    return  (int) encode($encoded, false);
}


function commission(User $user = null)
{
    $com = 0;
    if ($user) {
        $com = (float) ($user->commission);
    } else {
        $com = (float) (auth()->user()->commission);
    }
    return $com;
}

function code($lengh = 6)
{
    $c = '';
    while (1) {
        if ($lengh != 0) {
            $lengh--;
            $c .= rand(1, 9);
        } else {
            break;
        }
    }
    return $c;
}

function getconfig($name, User $user = null)
{
    if (!$user) {
        $user = auth()->user();
    }

    $conf = json_decode(@$user->configs()->first()->config ?? '[]');
    if (isset($conf->{$name})) {
        return $conf->{$name};
    }
    return null;
}

function setconfig($name, $value)
{
    if ($name and $value) {
        $user = auth()->user();
        $conf = (object) json_decode($user->configs()->first()->config ?? '[]');
        $conf->{$name} = $value;

        $o = $user->configs()->first();
        if ($o) {
            $o->update(['config' => json_encode($conf)]);
        } else {
            $user->configs()->create(['config' => json_encode($conf)]);
        }
    }
}

function paysources()
{
    return [
        'API',
        'E-PAY',
        'PAY-LINK'
    ];
}


function isvalidenumber($phone)
{
    return in_array(substr($phone, 0, 3), ['099', '097', '098', '090', '081', '082', '083', '084', '085', '080', '086']) and strlen($phone) == 10;
}
