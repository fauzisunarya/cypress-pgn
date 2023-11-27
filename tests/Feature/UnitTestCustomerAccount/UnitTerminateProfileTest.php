<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTerminateProfileTest extends TestCase
{
    
    public function testBerhasilTerminateDataCustomer()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "99"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/terminate', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnNotFound()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "000"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/terminate', $param);

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
                "nomor_pelanggan" => "123"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/terminate', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiMandatoryFieldRequired()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => ""
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/terminate', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

}
