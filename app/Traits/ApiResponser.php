<?php

namespace App\Traits;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(string $message = null, $data = null,  int $code = 200)
    {
        $resp = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($resp, $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, $data = null, int $code = 200)
    {
        $resp = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($resp, $code);
    }
}
