<?php

namespace App\Console\Commands;

use App\Models\Taux;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TauxUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = Http::get('https://control.gooomart.com/api/taux');
            $rep = $response->object();
            if ($rep->success) {
                $cdf_usd = $rep->CDF_USD;
                $usd_cdf = $rep->USD_CDF;
                $maj = $rep->maj;
                $taux = Taux::first();
                if (!$taux) {
                    Taux::create(['cdf_usd' => $cdf_usd, 'usd_cdf' => $usd_cdf, 'date' => (new \DateTime($maj))->format('Y-m-d H:i:s')]);
                } else {
                    $taux->update(['cdf_usd' => $cdf_usd, 'usd_cdf' => $usd_cdf, 'date' => (new \DateTime($maj))->format('Y-m-d H:i:s')]);
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
        return 0;
    }
}
