<?php

namespace Tests\Feature\UnitTestCustomerAccount;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitAddProfileBackOfficeTest extends TestCase
{

    public function testBehasilMelakukanRequestAddProfile()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "789003",
                "user_id" => "183"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add-account', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalRequestDenganInformasiLimitAccountReach()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "789003",
                "user_id" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add-account', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalRequestDenganInformasiDataNotFound()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "090",
                "user_id" => "183"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add-account', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
    public function testGagalRequestDenganInformasiInvalidToken()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "789003",
                "user_id" => "183"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/add-account', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
    public function testGagalRequestDenganInformasiMandatoryFieldRequired()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "",
                "user_id" => ""
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add-account', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
