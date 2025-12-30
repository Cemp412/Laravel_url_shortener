<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method
     * 
     * @return Illuminate\Http\Response
     */
    public function sendResponse($data, $message, $code = 200) {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * return error response
     * 
     * @return Illuminate\Http\Response
     */
    public function sendError($error, $errorMsg = [], $code= 500){
        $response = [
            'success' => false,
            'message' => $error
        ];

        if(!empty($errorMsg)) {
            $response['errors'] = $errorMsg;
        }

        return response()->json($response, $code);
    }
}
