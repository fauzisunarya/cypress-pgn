<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestUpdateCustomerTest extends TestCase
{

    public function testBehasilUpdateCustomer()
    {

        $param = array(
            "data" => array(
                "nomor_pelanggan" => "202300001",
                "first_name" => "rijky ke 2",
                "last_name" => "sallaffudin",
                "birth_place" => "bandung",
                "birth_date" => "1990-11-28",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "Esih Mintarsih",
                "last_education" => "oke coba",
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
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnUnatuhorized()
    {

        $param = array(
            "data" => array(
                "nomor_pelanggan" => "2023",
                "first_name" => "rijky ke 2",
                "last_name" => "sallaffudin",
                "birth_place" => "bandung",
                "birth_date" => "1990-11-28",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "Esih Mintarsih",
                "last_education" => "oke coba",
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
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnDataNotFound()
    {

        $param = array(
            "data" => array(
                "nomor_pelanggan" => "202",
                "first_name" => "rijky ke 2",
                "last_name" => "sallaffudin",
                "birth_place" => "bandung",
                "birth_date" => "1990-11-28",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "Esih Mintarsih",
                "last_education" => "oke coba",
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
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/customer/update', $param);

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
                "nomor_pelanggan" => "",
                "first_name" => "",
                "last_name" => "",
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
            ->post('/api/customer/update', $param);

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
                "nomor_pelanggan" => "202300001",
                "first_name" => "rijky ke 2",
                "last_name" => "sallaffudin",
                "birth_place" => "bandung",
                "birth_date" => "1990-11-28",
                "occupation" => "Karyawan Swasta",
                "gender" => "Laki-Laki",
                "blood_type" => "A",
                "mother_name" => "Esih Mintarsih",
                "last_education" => "oke coba",
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
            ->post('/api/customer/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
