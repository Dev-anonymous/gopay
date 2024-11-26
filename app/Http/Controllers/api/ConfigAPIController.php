<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigAPIController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $action = request('action');
        $name = request('name');
        $value = request('value');

        if ('set' == $action) {

            $user = auth()->user();
            $validator = Validator::make(request()->all(), [
                'source' => 'required|array|in:' . implode(',', paysources()),
            ]);
            if ($validator->fails()) {
                return $this->error(implode(' ', $validator->errors()->all()));
            }
            $input = [];

            $errors = [];

            foreach (paysources() as $source) {
                $perc = (array) request("percent_$source");
                $phone = (array) request("phone_$source");

                if (count($perc) != count($phone)) {
                    $errors[] = "Count(phone) != count(percent)";
                }

                foreach ($phone as $p) {
                    $tel = (int) $p;
                    $tel = "0$tel";
                    if (!isvalidenumber($tel)) {
                        $errors[] = "[via $source] : Le numéro $tel n'est pas valide";
                    }
                }

                if (array_sum($perc) != 100 && count($phone)) {
                    $errors[] = "[via $source] : La répartition de pourcentage est invalide.";
                }

                $ph = [];
                foreach ($phone as $e) {
                    $ph[] = "0$e";
                }
                $phone = $ph;
                $input[] = (object) ['source' => $source, 'phone' => $phone, 'percent' => $perc];
            }

            if (count($errors)) {
                return $this->error(implode(', ', $errors));
            }
            setconfig('autosenddata', json_encode($input));

            return $this->success("Votre configuration a été sauvegardée.");
            
        } else {
            if ($name == 'autosend' && in_array($value, ['yes', 'no'])) {
                setconfig($name, $value);
                return 'ok';
            }
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy(Config $config)
    {
        //
    }
}
