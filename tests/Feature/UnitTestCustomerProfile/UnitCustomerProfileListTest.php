<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UnitCustomerProfileListTest extends TestCase
{

    public function testBerhasilMemunculkanPoint()
    {

        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "id",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "budi",
                "status" => "1",
                "min_data" => "",
                "user_id" => ""
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/customer/list', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnCustomerListTidakDitemukan()
    {

        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "id",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "joko",
                "status" => "1",
                "min_data" => "",
                "user_id" => ""
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/customer/list', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    // public function testGagalReturnMandatoryFieldRequired()
    // {

    //     $param = array(
    //         "data" => array(
    //             "order" => array(
    //                 "column" => "",
    //                 "dir" => ""
    //             ),
    //             "start" => "",
    //             "length" => "",
    //             "search" => "joko",
    //             "status" => "1",
    //             "min_data" => "",
    //             "user_id" => ""
    //         )
    //     );

    //     /** get api Education */
    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $this->_token
    //     ])
    //         ->post('/api/customer/list', $param);


    //     /** mengambil response body dari get api Education */
    //     $content = $response->decodeResponseJson();
    //     $code = $content['code'];

    //     /** ekspetasi response api dengan testcase ini */
    //     $this->assertNotEquals($code, 0);
    // }

    public function testGagalReturnInformasiInvalidToken()
    {

        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "id",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "budi",
                "status" => "1",
                "min_data" => "",
                "user_id" => ""
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/customer/list', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

}
