<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitUpdateProfileApprovalTest extends TestCase
{

    public function testBerhasilMelakukanUpdateRequest()
    {
        $param = array(
            "data" => array(
                "request_id" => "55",
                "status" => "2",
                "nomor_pelanggan" => "123000",
                "type" => "1",
                "name" => "admin uas edit",
                "identity" => "234567875678654",
                "phone_number" => "089790319299",
                "last_payment" => "",
                "district" => "bandung",
                "serial_number" => "000223212",
                "remark" => "ok"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/update-status', $param);

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
                "request_id" => "9999",
                "status" => "2",
                "nomor_pelanggan" => "99",
                "type" => "1",
                "name" => "rijky ke 2",
                "identity" => "234567875678654",
                "phone_number" => "234567875678654",
                "last_payment" => "",
                "district" => "bandung",
                "serial_number" => "",
                "remark" => "ok"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/update-status', $param);

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
                "request_id" => "9999",
                "status" => "2",
                "nomor_pelanggan" => "99",
                "type" => "1",
                "name" => "rijky ke 2",
                "identity" => "234567875678654",
                "phone_number" => "234567875678654",
                "last_payment" => "",
                "district" => "bandung",
                "serial_number" => "",
                "remark" => "ok"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/update-status', $param);

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
                "request_id" => "",
                "status" => "",
                "nomor_pelanggan" => "",
                "type" => "",
                "name" => "",
                "identity" => "",
                "phone_number" => "",
                "last_payment" => "",
                "district" => "",
                "serial_number" => "",
                "remark" => ""
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/update-status', $param);

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
                "request_id" => "22",
                "status" => "2",
                "nomor_pelanggan" => "99",
                "type" => "1",
                "name" => "rijky ke 2",
                "identity" => "234567875678654",
                "phone_number" => "234567875678654",
                "last_payment" => "",
                "district" => "bandung",
                "serial_number" => "",
                "remark" => "ok"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/update-status', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
