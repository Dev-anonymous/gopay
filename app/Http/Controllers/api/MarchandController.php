<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\LienPaie;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\SendMoney;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;

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
                $dev = explode(' ', $sol);
                $dev = end($dev);
                if ($dev == $devise) {
                    $s[] = $sol;
                    $r = $s;
                    break;
                }
            }
        }
        return $this->success('SOLDE', $r);
    }

    public function transaction($limte = null)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $source = request()->source;

        if (request()->has('datatable')) {
            $data = Transaction::where('compte_id', $compte->id);
            $dtable = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('montant', function ($data) {
                    return formatMontant($data->montant, $data->devise->devise);
                })->editColumn('date', function ($data) {
                    return $data->date->format('d-m-Y H:i:s');;
                });
            if ($source == 'E-PAY') {
                $data = $data->where('source', $source);
                $dtable = $dtable->addColumn('numero', function ($data) {
                    $d = json_decode($data->data);
                    $tel = mask_num(@$d->telephone);
                    $ref = @$d->ref;
                    $h = "$tel<br><small style='font-size:10px'>$ref</small>";
                    return $h;
                })->escapeColumns([3]);
            }
            return $dtable->make(true);
        }

        $trans = Transaction::where('compte_id', $compte->id);
        $limte = (int) $limte;
        if ($limte) {
            $trans = $trans->limit($limte);
        }

        if ($source == 'E-PAY') {
            $trans = $trans->where('source', $source);
        }

        $trans = $trans->orderBy('id', 'desc')->paginate();

        $tab = [];
        foreach ($trans->getCollection() as  $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->trans_id = $e->trans_id;
            $a->montant = formatMontant($e->montant, $e->devise->devise);
            $a->type = $e->type;
            $a->source = $e->source;
            $d = json_decode($e->data);
            $a->tel = mask_num(@$d->telephone);
            $a->ref = @$d->ref;
            $a->date = $e->date->format('d-m-Y H:i:s');
            array_push($tab, $a);
        }

        $trans = $trans->toArray();
        $trans['data'] = $tab;

        $m = "TRANSACTIONS";
        return $this->success($m, $trans);
    }

    public function transaction_recentes()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $trans = Transaction::where('compte_id', $compte->id)->orderBy('id', 'desc')->limit(5)->get();
        $idsol = $user->comptes()->first()->soldes()->pluck('id')->all();
        $demande = DemandeTransfert::whereIn('solde_id', $idsol)->orderBy('id', 'DESC')->limit(5)->get();

        $tab = [];

        foreach ($trans as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->dir = "IN";
            $a->trans_id = $e->trans_id;
            $a->montant = formatMontant($e->montant, $e->devise->devise);
            $a->type = $e->type;
            $a->source = $e->source;
            $d = json_decode($e->data);
            $a->tel = mask_num(@$d->telephone);
            $a->ref = @$d->ref;
            $a->date = $e->date->format('d-m-Y H:i:s');
            array_push($tab, $a);
        }
        foreach ($demande as $e) {
            $o = (object)[];
            $o->id = $e->id;
            $o->dir = "OUT";
            $o->trans_id = $e->trans_id;
            $o->montant = formatMontant($e->montant, $e->solde->devise->devise);
            $o->au_numero = $e->au_numero;
            $o->status = $e->status;
            $o->note_validation = $e->note_validation;
            $o->date = $e->date->format('d-m-Y H:i:s');
            $o->date_validation = $e->date_validation?->format('d-m-Y H:i:s');
            array_push($tab, $o);
        }

        usort($tab,  function ($a, $b) {
            return strtotime($a->date) < strtotime($b->date);
        });

        $m = "TRANSACTIONS RECENTES";
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
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }
        $devise = request()->devise;
        $montant = request()->montant;
        $telephone = request()->telephone;
        if ($devise == 'CDF' and $montant < 2000) {
            return $this->error("Le montant minimum de transfert est de 2000 CDF");
        } else {
            if ($montant < 1) {
                return $this->error("Le montant minimum de transfert est de 1 USD");
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

        $comm = $montant * commission();
        $m =  $montant + $comm;

        if ($montant_solde < $m) {
            return $this->error("Vous disposez de $montant_solde {$solde->devise->devise} dans votre compte, votre demande de transfert de $m {$solde->devise->devise} ne peut etre enregistrée pour le moment.", 200);
        }

        DB::beginTransaction();
        DemandeTransfert::create([
            'solde_id' => $solde->id,
            'au_numero' => $telephone, 'montant' => $montant,
            'date' => now('Africa/Lubumbashi'),
            'trans_id' => trans_id('CASH.OUT', $user)
        ]);
        $admin = User::where('user_role', 'admin')->first();
        $sent = false;
        try {
            $c = commission($user) * 100;
            $mo = formatMontant($montant, $devise);
            $so = formatMontant($montant_solde, $devise);
            $m = "Demande de transfert de $user->business_name, $user->name </br>Montant : $mo au $telephone </br> Solde : $so </br> Commission: $c %";
            $admin->notify(new SendMoney($m));
            $sent = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        if (!$sent) {
            DB::rollBack();
            return $this->error("Un petit problème est survenu, veuillez réessayer SVP.");
        } else {
            DB::commit();
        }

        return $this->success("Votre demande de transfert a été enregistrée et sera traité sous peu. Merci.");
    }

    public function get_demande_tranfert()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $idsol = $user->comptes()->first()->soldes()->pluck('id')->all();
        if (request()->has('datatable')) {
            $data = DemandeTransfert::whereIn('solde_id', $idsol);
            $dtable = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    $dt = '';
                    if ($data->status == 'EN ATTENTE') {
                        $status = "<span class='badge w-100 bg-warning p-2'>$data->status</span>";
                    } elseif ($data->status == 'TRAITÉE') {
                        $status = "<span class='badge w-100 bg-success p-2'>$data->status</span>";
                        $dt = "Le {$data->date_validation?->format('d-m-Y H:i:s')}";
                    } else {
                        $status = "<span class='badge w-100 bg-danger p-2'>$data->status</span>";
                        $dt = "Le {$data->date_validation?->format('d-m-Y H:i:s')}";
                    }
                    $s = $status . "<br><small class='text-muted mt-1' style='font-size:10px'>$dt</small>";
                    return $s;
                })
                ->editColumn('montant', function ($data) {
                    return formatMontant($data->montant, $data->solde->devise->devise);
                })->editColumn('date', function ($data) {
                    return $data->date?->format('d-m-Y H:i:s');
                })->rawColumns(['status']);

            return $dtable->make(true);
        }

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
            $o->date_validation = $e->date_validation?->format('d-m-Y H:i:s');
            array_push($tab, $o);
        }

        $data = $demande->toArray();
        $data['data'] = $tab;

        return $this->success("DEMANDES DE TRANSFERT", $data);
    }

    public function numero_compte()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $compte = $user->comptes()->first();
        $n = $compte->numero_compte;
        return $this->success("NUMERO DE COMPTE", $n);
    }

    public function pay_init()
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
        $devise = request()->devise;
        $montant = request()->amount;
        $telephone = request()->telephone;

        if ($devise == 'CDF' and $montant < 500) {
            return $this->error("Le montant minimum de paiement est de 500 CDF");
        } else {
            if ($montant < 1) {
                return $this->error("Le montant minimum de paiement est de 1 USD");
            }
        }
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $key = $user->apikeys()->where('type', 'production')->first()->key;


        $params = [
            '_source' => 'E-PAY',
            'devise' => $devise,
            'amount' => $montant,
            'telephone' => $telephone
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

    public function pay_check()
    {
        $ref = request()->ref;
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $key = $user->apikeys()->where('type', 'production')->first()->key;
        $request = Request::create(route('pay.check', $ref));
        $request->headers->set('x-api-key', $key);
        $req = app()->handle($request);
        if ($req->status() != 200) {
            return $this->error("Une erreur s'est produite, veuillez réessayer.");
        } else {
            $data = json_decode($req->getContent());
        }

        return response()->json((array) $data);
    }

    public function getpay_link()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $idcompte = $user->comptes()->first()->id;
        if (request()->has('datatable')) {
            $data = LienPaie::where('compte_id', $idcompte);
            $dtable = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $href = makepay_link($data->id);
                    $action =
                        <<<DATA
                    <input value='$href' id='lien-$data->id' class='d-none'>
                    <button class="btn btn-link dropdown-toggle mr-4 text-dark" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                       <i class='fa fa-trash'></i> Supprimer
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" id="$data->id" deletelink href="#">Confirmer</a>
                    </div>
                    DATA;
                    return $action;
                })
                ->editColumn('montant', function ($data) {
                    return formatMontant($data->montant, $data->devise);
                })->editColumn('devise_fixe', function ($data) {
                    if ($data->devise_fixe) {
                        $dfixe =
                            '<span class="badge w-100 p-2 bg-success" style="cursor:pointer" title="Le payeur ne pourra pas payer en une autre devise que celle que avez renseigné." >OUI</span>';
                    } else {
                        $dfixe =
                            '<span class="badge w-100 p-2 bg-warning" style="cursor:pointer" title="Le payeur pourra payer en une autre devise CDF ou USD." >NON</span>';
                    }
                    return $dfixe;
                })->editColumn('montant_fixe', function ($data) {
                    if ($data->montant_fixe) {
                        $mfixe =
                            '<span class="badge w-100 p-2 bg-success" style="cursor:pointer" title="Le payeur va payer exactement ' . formatMontant($data->montant, $data->devise) . '" >OUI</span>';
                    } else {
                        $mfixe =
                            '<span class="badge w-100 p-2 bg-warning" style="cursor:pointer" title="Le payeur pourra payer  un montant different de ' . formatMontant($data->montant, $data->devise) . '" >NON</span>';
                    }
                    return $mfixe;
                })->addColumn('lien', function ($data) {
                    $href = makepay_link($data->id);
                    $lien =
                        "<a href='$href' target='_blank' class='btn btn-link'><i class='fa fa-globe-africa'></i> Lien</a>";
                    $lien .= "<button class='btn btn-sm btn-copy' value='$data->id'><i class='fa fa-copy'></i><span style='font-size:15px; text-transform:none'></span></button>";
                    return $lien;
                })->editColumn('date', function ($data) {
                    return $data->date->format('d-m-Y H:i:s');;
                })->escapeColumns([]);
            return $dtable->make(true);
        }

        $links = LienPaie::where('compte_id', $idcompte)->orderBy('id', 'DESC')->paginate();
        $tab = [];
        foreach ($links->getCollection() as $e) {
            $a = (object) [];
            $a->id = $e->id;
            $a->nom = $e->nom;
            $a->montant = formatMontant($e->montant, $e->devise);
            $a->montant_fixe = $e->montant_fixe;
            $a->devise_fixe = $e->devise_fixe;
            $a->date = $e->date->format('d-m-Y H:i:s');
            $a->lien = makepay_link($e->id);
            $tab[] = $a;
        }

        $links = $links->toArray();
        $links['data'] = $tab;

        return $this->success("LIENS DE PAIEMENTS", $links);
    }

    public function pay_link()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'devise' => 'required|in:CDF,USD',
                'amount' => 'required|numeric|',
                'name' => 'required|max:100',
                'montant_fixe' => 'required|in:1,0',
                // 'devise_fixe' => 'required|in:1,0',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', ['errors_msg' => $validator->errors()->all()]);
        }

        $nom = request()->name;
        $devise = request()->devise;
        $montant = request()->amount;
        $montant_fixe = request('montant_fixe');
        $devise_fixe = 1; // request('devise_fixe');

        if ($devise == 'CDF' and $montant < 500) {
            return $this->error("Le montant minimum de paiement est de 500 CDF");
        } else {
            if ($montant < 1) {
                return $this->error("Le montant minimum de paiement est de 1 USD");
            }
        }

        $data = compact('nom', 'devise', 'montant', 'montant_fixe', 'devise_fixe');
        $data['date'] = now('Africa/Lubumbashi');
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $idcompte = $user->comptes()->first()->id;
        $data['compte_id'] = $idcompte;

        $exist = LienPaie::where(['compte_id' => $idcompte, 'nom' => $nom])->first();
        if ($exist) {
            return $this->error("Le lien de paiement \"$nom\" existe déjà.");
        }
        LienPaie::create($data);

        return $this->success("Votre lien de paiemen a été créé.");
    }

    public function pay_link_del(LienPaie $id)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $idcompte = $user->comptes()->first()->id;
        if ($id->compte_id != $idcompte) {
            abort(403);
        }
        $id->delete();
        return $this->success('Lien de paiement supprimé');
    }

    public function pin_check()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $validator = Validator::make(
            request()->all(),
            [
                'pin' => 'required|integer',
            ]
        );
        if ($validator->fails()) {
            return $this->error(implode(', ', $validator->errors()->all()));
        }
        $pin = request('pin');

        if ($pin != $user->pin) {
            return $this->error("Pin non valide");
        }

        return $this->success('Pin valide');
    }
}
