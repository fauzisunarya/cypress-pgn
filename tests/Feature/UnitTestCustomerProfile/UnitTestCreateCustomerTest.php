<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestCreateCustomerTest extends TestCase
{

    public function testBehasilCreateCustomer()
    {

        $param = array(
            "data" => array(
                "customer_name" => "Budiana",
                "birth_place" => "bandung",
                "birth_date" => "1990-08-11",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "",
                "last_education" => "",
                "customer_alias" => "",
                "religion" => "Islam",
                "customer_category" => "PERSONAL",
                "source_application" => "PGN",
                "source" => "PGN",
                "cust_status" => "1",
                "image_profile" => "base 64 string",
                "image_signature" => "base 64 string"
            )
        );
    

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenUserLain,
        ])
            ->post('/api/customer/create', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnMandatoryFieldRequired()
    {

        $param = array(
            "data" => array(
                "customer_name" => "",
                "birth_place" => "",
                "birth_date" => "",
                "occupation" => "Karyawan Swasta",
                "gender" => "",
                "blood_type" => "A",
                "mother_name" => "",
                "last_education" => "",
                "customer_alias" => "",
                "religion" => "Islam",
                "customer_category" => "",
                "source_application" => "PGN",
                "source" => "",
                "cust_status" => "1",
                "image_profile" => "base 64 string",
                "image_signature" => "base 64 string"
            )
        );
    

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/create', $param);

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
                "customer_name" => "Budiana",
                "birth_place" => "bandung",
                "birth_date" => "1990-08-11",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "",
                "last_education" => "",
                "customer_alias" => "",
                "religion" => "Islam",
                "customer_category" => "PERSONAL",
                "source_application" => "PGN",
                "source" => "PGN",
                "cust_status" => "1",
                "image_profile" => "base 64 string",
                "image_signature" => "base 64 string"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/customer/create', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnUserTelahMemilikiAkunCustomer()
    {

        $param = array(
            "data" => array(
                "customer_name" => "Budiana",
                "birth_place" => "bandung",
                "birth_date" => "1990-08-11",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "",
                "last_education" => "",
                "customer_alias" => "",
                "religion" => "Islam",
                "customer_category" => "PERSONAL",
                "source_application" => "PGN",
                "source" => "PGN",
                "cust_status" => "1",
                "image_profile" => "base 64 string",
                "image_signature" => "base 64 string"
            )
        );
    

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenUserLain,
        ])
            ->post('/api/customer/create', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
