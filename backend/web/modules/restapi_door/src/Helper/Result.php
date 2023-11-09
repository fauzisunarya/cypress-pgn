<?php

namespace Drupal\restapi_door\Helper;

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
}