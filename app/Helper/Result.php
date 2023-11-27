<?php

namespace App\Helper;

class Result
{
    const STATUS_SUCCESS = 0;
    const HTTP_SUCCESS = 200;
    const HTTP_REQUEST_SUCCESS = 201;
    const HTTP_BAD_REQUEST = 400;

    public $code;
    public $info;
    public $data;
    public $status;

    function __construct($code = self::STATUS_SUCCESS,$info = 'success', $data = [], $status = self::HTTP_SUCCESS){
        $this->code = $code;
        $this->info = $info;
        $this->data = $data;
        $this->status = $status;
    }

    public function sendResponse($data, $info)
    {
        $this->code = 0;
        $this->info = $info;
        $this->data = $data;
    }

    public function sendError($error, $code = 404)
    {
        $this->code = $code;
        $this->info = $error;
        $this->data = [];
        $this->status = $code;
    }

    public function sendSuccess($message)
    {
        $this->code = 0;
        $this->info = $message;
        $this->data = [];
        $this->status = 200;
    }
}