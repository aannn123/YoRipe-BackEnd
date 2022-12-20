<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJson($code, $status, $msg, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];

        return response()->json($response, $code);
    }

    public function responseValidate($code, $status, $msg, $errors)
    {
        $response = [
            'status' => $status,
            'message' => $msg,
            'errors' => $errors
        ];

        return response()->json($response, $code);
    }
}
