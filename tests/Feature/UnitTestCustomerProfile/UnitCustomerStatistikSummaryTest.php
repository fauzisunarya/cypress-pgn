<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitCustomerStatistikSummaryTest extends TestCase
{

    public function testBerhasilMemunculkanStatistik()
    {

        $param = array(
            "data" => array(
                "type" => "4",
                "startdate" => "20230101",
                "enddate" => "20231107"

            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/customer/statistics/summary', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalMemunculkanStatistikDenganInformasiDataNotFound()
    {

        $param = array(
            "data" => array(
                "type" => "99999",
                "startdate" => "20231107",
                "enddate" => "20231107"

            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/customer/statistics/summary', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalMemunculkanStatistikDenganInformasiMandatoryFieldNotFound()
    {

        $param = array(
            "data" => array(
                "type" => "4",
                "startdate" => "",
                "enddate" => ""

            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/customer/statistics/summary', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
    public function testGagalMemunculkanStatistikDenganInformasiUnauthorized()
    {

        $param = array(
            "data" => array(
                "type" => "4",
                "startdate" => "20231106",
                "enddate" => "20231107"

            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/customer/statistics/summary', $param);


        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
