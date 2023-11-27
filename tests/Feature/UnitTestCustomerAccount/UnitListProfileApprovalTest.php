<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitListProfileApprovalTest extends TestCase
{

    public function testBerhasilMemunculkanRequestList()
    {
        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "create_dtm",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "99",
                "status" => "2"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/request-list', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnRequestListTidakDitemukan()
    {
        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "create_dtm",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "202020202",
                "status" => "1"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/request-list', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalUnauthorized()
    {
        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "create_dtm",
                    "dir" => "asc"
                ),
                "start" => "0",
                "length" => "10",
                "search" => "202020202",
                "status" => "1"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/request-list', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnMandatoryFieldRequired()
    {
        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "",
                    "dir" => "asc"
                ),
                "start" => "",
                "length" => "",
                "search" => "202020202",
                "status" => "1"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/request-list', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiInvalidToken()
    {
        $param = array(
            "data" => array(
                "order" => array(
                    "column" => "",
                    "dir" => "asc"
                ),
                "start" => "",
                "length" => "",
                "search" => "202020202",
                "status" => "1"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/request-list', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
