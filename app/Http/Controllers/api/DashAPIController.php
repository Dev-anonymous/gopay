<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfert;
use App\Models\Devise;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $year = request()->year ?? date('Y');


        $series = [];
        if ('marchand' == $user->user_role) {
            $compte = $user->comptes()->first();
            $solde = tot_solde_marchand($compte->id);
            $s = [];
            foreach ($solde as $k => $v) {
                $s[] = formatMontant($v, $k);
            }
            $solde = $s;
            $trans = Transaction::where('compte_id', $compte->id)->whereYear('date', $year)->count();

            $idsol = $user->comptes()->first()->soldes()->pluck('id')->all();
            $transfert =  DemandeTransfert::whereIn('solde_id', $idsol)->whereYear('date', $year)->count();

            $sources = Transaction::where('compte_id', $compte->id)->whereYear('date', $year)->groupBy('source')->pluck('source')->all();

            $lab = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];

            $cdfid = Devise::where('devise', 'CDF')->first()->id;
            $usdid = Devise::where('devise', 'USD')->first()->id;

            $tab = [];

            foreach ($sources as $source) {
                $lab0 = "$source CDF";
                $lab1 = "$source USD";

                $scdf = [];
                $susd = [];

                foreach (range(1, 12) as $k => $m) {
                    $d = Transaction::selectRaw('sum(montant) as montant')->whereMonth('date', $m)->whereYear('date', $year)->where([
                        'source' => $source,
                        'compte_id' => $compte->id,
                        'devise_id' => $cdfid,
                    ])->first();
                    $d1 = Transaction::selectRaw('sum(montant) as montant')->whereMonth('date', $m)->whereYear('date', $year)->where([
                        'source' => $source,
                        'compte_id' => $compte->id,
                        'devise_id' => $usdid,
                    ])->first();

                    $scdf[] = (object) ['x' => $lab[$k], 'y' => (float) $d->montant];
                    $susd[] = (object) ['x' => $lab[$k], 'y' => (float) $d1->montant];
                }

                $tab[$lab0] = $scdf;
                $tab[$lab1] = $susd;
            }

            foreach ($tab as $k => $v) {
                $series[] = (object) [
                    "type" => 'API CDF' == $k ? 'area' : 'line',
                    'name' => $k,
                    'data' => $v
                ];
            }
        }

        $data = [];
        $data['solde'] = $solde;
        $data['transfert'] = $transfert;
        $data['trans'] = $trans;
        $data['transchart'] = $series;

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
