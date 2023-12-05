<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Helper\Result;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function inisiateResult($code = Result::STATUS_SUCCESS,$info = 'success', $data = [], $status = Result::HTTP_SUCCESS){
        return new Result($code,$info,$data,$status);
    }

    public function sendResult(Result $result)
    {
        return response()->json([
            'code' => $result->code,
            'info' => $result->info,
            'data' => $result->data
        ], $result->status);
    }

    public function sendResponse($data, $info)
    {
        $result = $this->inisiateResult(0, $info,$data);
        return $this->sendResult($result);
    }

    public function sendError($error, $code = 404)
    {
        $result = $this->inisiateResult();
        $result->sendError($error, $code);
        return $this->sendResult($result);
    }

    public function sendSuccess($message)
    {
        $result = $this->inisiateResult();
        $result->sendSuccess($message);
        return $this->sendResult($result);
    }
}
