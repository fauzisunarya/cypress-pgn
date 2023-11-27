<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestCustomerInfoTest extends TestCase
{

    public function testBerhasilMemunculkanLoadCustomerProfile()
    {

        $param = array(
            "code" => 4,
            "data" => array(
                "key" => "292903929922"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/info', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnLoadCustomerProfileTidakDitemukan()
    {

        $param = array(
            "code" => 6,
            "data" => array(
                "key" => "2929039292"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/info', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiInvalidToken()
    {

        $param = array(
            "code" => 1,
            "data" => array(
                "key" => "79"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/customer/info', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiUnauthorize()
    {

        $param = array(
            "code" => 1,
            "data" => array(
                "key" => ""
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => null,
        ])
            ->post('/api/customer/info', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiMandatoryFieldRequired()
    {

        $param = array(
            "code" => 0,
            "data" => array(
                "key" => ""
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => null,
        ])
            ->post('/api/customer/info', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
