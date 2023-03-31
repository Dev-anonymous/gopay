<?php

namespace App\Http\Middleware\app;

use App\Models\Apikey;
use Closure;
use Illuminate\Http\Request;

class PaymentTest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apikey = $request->header('x-api-key');
        if (!$apikey) {
            abort(403, "API Key is required");
        }
        $at = Apikey::where('key', $apikey)->first();
        if (!$at) {
            abort(403, "Invalid API Key");
        }
        if ($at->type != 'test') {
            abort(403, "You must use test API key");
        }
        if ($at->active != '1') {
            abort(403, "Your API key is rejected");
        }
        return $next($request);
    }
}
