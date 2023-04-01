<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Apikey;
use App\Models\Compte;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Feedback;
use App\Models\Solde;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AdminController extends Controller
{
    use ApiResponser;

    public function feedback()
    {
        $data = Feedback::orderBy('id', 'desc')->get();
        return $this->success("Feedback", $data);
    }

    public function marchand()
    {
        $data = User::orderBy('id', 'desc')->where('user_role', 'marchand')->get();
        $tab = [];
        foreach ($data as $e) {
            $o = (object)[];
            $o->id = $e->id;
            $o->name = $e->name;
            $o->phone = $e->phone;
            $o->email = $e->email;
            $o->user_role = $e->user_role;
            $cmpt = Compte::where('users_id', $e->id)->first();
            $solde = $cmpt->soldes()->get();
            $s = [];
            foreach ($solde as $so) {
                array_push($s, formatMontant($so->montant, $so->devise->devise));
            }
            $o->solde = implode(',', $s);
            $o->numero_compte = $cmpt->numero_compte;
            $o->apikey = $e->apikeys()->get(['id', 'key', 'type', 'active']);
            array_push($tab, $o);
        }
        return $this->success('Marchands', $tab);
    }

    public function marchand_add(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:45',
                'email' => 'sometimes|email|max:255|unique:users',
                'phone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone',
                'password' => 'required|string|min:6|',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }

        $em = request('email');
        $ph = request('phone');
        if (empty($em) and empty($ph)) {
            return $this->error('Erreur', 400, ['errors_msg' => ["Vous devez spécifier soit votre email, soit le numéro de téléphone pour créer un compte."]]);
        }

        $data = $validator->validate();
        $data['password'] = Hash::make($data['password']);

        DB::beginTransaction();
        try {
            $data['avatar'] = '';
            $user = User::create($data);
            $cmpt =  Compte::create([
                'users_id' => $user->id,
                'numero_compte' => numeroCompte()
            ]);
            $dev = Devise::all();
            foreach ($dev as $d) {
                Solde::create(['montant' => 0, 'devise_id' => $d->id, 'compte_id' => $cmpt->id]);
            }

            Apikey::create(['users_id' => $user->id, 'key' => encode(time() * rand(2, 100)), 'type' => 'production']);
            Apikey::create(['users_id' => $user->id, 'key' => encode(time() * rand(2, 100)), 'type' => 'test']);

            DB::commit();

            return $this->success("Le compte a été créé avec succès.");
        } catch (\Exception $th) {
            DB::rollBack();
            return $this->error('Erreur', 200, ['errors_msg' => ["Une erreur s'est produite lors de la création du compte."]]);
        }
    }

    public function transaction()
    {
        $trans = Transaction::orderBy('id', 'desc')->get();

        $tab = [];
        foreach ($trans as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->user = $e->compte->user->name;
            $a->numero_compte = $e->compte->numero_compte;
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

        $m = "Transactions";
        return $this->success($m, $tab);
    }

    public function envoi_fonds()
    {
        $trans = DemandeTransfert::orderBy('status')->get();
        $tab = [];
        foreach ($trans as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->marchand = $e->solde->compte->user->name;
            $a->numero_compte = $e->solde->compte->numero_compte;
            $a->au_numero = $e->au_numero;
            $a->montant = formatMontant($e->montant, $e->solde->devise->devise);

            $solde = $e->solde->compte->soldes()->get();
            $s = [];
            foreach ($solde as $so) {
                array_push($s, formatMontant($so->montant, $so->devise->devise));
            }
            $a->solde = implode(',', $s);
            $a->status = $e->status;
            $a->note_validation = $e->note_validation;
            $a->date = $e->date->format('d-m-Y H:i:s');
            array_push($tab, $a);
        }

        $m = "Demandes tranfert fonds";
        return $this->success($m, $tab);
    }

    public function maj_envoi_fonds()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'id' => 'required|exists:demande_transfert',
                'status' => 'required|in:TRAITÉE,REJETÉE',
                'note_validation' => 'sometimes|max:255',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }

        $data = $validator->validated();
        $data['note_validation'] = request()->note_validation ?? "Demande traité avec succès";
        $id = $data['id'];
        unset($data['id']);
        DemandeTransfert::where('id', $id)->update($data);
        return $this->success("La demande a été mise à jour.");
    }

    public function apikey_status()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'id' => 'required|exists:apikey',
                'active' => 'required|in:1,0',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }
        $data = $validator->validated();
        unset($data['id']);
        $k =  Apikey::where('id', request()->id)->first();
        $k->update($data);

        if (request()->status == 1) {
            $m = "La clé API($k->type) du marchand {$k->user->name} a été activée.";
        } else {
            $m = "La clé API($k->type) du marchand {$k->user->name} a été bloquée.";
        }
        return $this->success($m);
    }
}
