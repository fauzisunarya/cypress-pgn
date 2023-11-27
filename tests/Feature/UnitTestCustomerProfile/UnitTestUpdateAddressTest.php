<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestUpdateAddressTest extends TestCase
{

    public function testBehasilUpdateAddress()
    {

        $param = array(
            "data" => array(
                "address_id" => "61",
                "addr_type" => "1",
                "addr_desc" => "Griya hamdan asri yang udah di edit 2 kali",
                "country" => "Indonesia",
                "house_no" => "05",
                "rt" => "05",
                "rw" => "11",
                "latitude" => "321321321321",
                "longitude" => "432543254325",
                "street_name" => "Cingised",
                "area_name" => "Arcamanik",
                "district_name" => "Cisaranten",
                "district_id" => "218",
                "city_name" => "Kota Bandung",
                "city_id" => "5",
                "province" => "Jawa Barat",
                "province_id" => "1",
                "postal_code" => "40295",
                "end_dtm" => "",
                "source" => "PGN"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/address/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnunatuhorized()
    {

        $param = array(
            "data" => array(
                "address_id" => "45",
                "addr_type" => "1",
                "addr_desc" => "Griya hamdan asri yang udah di edit 2 kali",
                "country" => "Indonesia",
                "house_no" => "05",
                "rt" => "05",
                "rw" => "11",
                "latitude" => "321321321321",
                "longitude" => "432543254325",
                "street_name" => "Cingised",
                "area_name" => "Arcamanik",
                "district_name" => "Cisaranten",
                "district_id" => "218",
                "city_name" => "Kota Bandung",
                "city_id" => "5",
                "province" => "Jawa Barat",
                "province_id" => "1",
                "postal_code" => "40295",
                "end_dtm" => "",
                "source" => "PGN"
            )
        );



        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/address/update', $param);

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
                "address_id" => "",
                "customer_id" => "",
                "addr_type" => "",
                "addr_desc" => "",
                "country" => "",
                "house_no" => "",
                "rt" => "",
                "rw" => "",
                "latitude" => "",
                "longitude" => "",
                "street_name" => "Cingised",
                "area_name" => "",
                "district_name" => "",
                "district_id" => "218",
                "city_name" => "",
                "city_id" => "5",
                "province" => "",
                "province_id" => "1",
                "postal_code" => "",
                "end_dtm" => "",
                "source" => "PGN"
            )
        );



        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/address/update', $param);

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
                "address_id" => "61",
                "addr_type" => "1",
                "addr_desc" => "Griya hamdan asri yang udah di edit 2 kali",
                "country" => "Indonesia",
                "house_no" => "05",
                "rt" => "05",
                "rw" => "11",
                "latitude" => "321321321321",
                "longitude" => "432543254325",
                "street_name" => "Cingised",
                "area_name" => "Arcamanik",
                "district_name" => "Cisaranten",
                "district_id" => "218",
                "city_name" => "Kota Bandung",
                "city_id" => "5",
                "province" => "Jawa Barat",
                "province_id" => "1",
                "postal_code" => "40295",
                "end_dtm" => "",
                "source" => "PGN"
            )
        );



        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/address/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
