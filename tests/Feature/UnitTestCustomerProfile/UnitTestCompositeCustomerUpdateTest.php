<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestCompositeCustomerUpdateTest extends TestCase
{

    public function testBehasilUbahCustomer()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "78",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "55",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "42",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "77",
                        "contact_desc" => "0850000000",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "78",
                        "contact_desc" => "rijky@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnUxnauthorized()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "76",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "55",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "42",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "77",
                        "contact_desc" => "0850000000",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "78",
                        "contact_desc" => "rijky@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnIdentityIdAddressIdDanContactIdNotFound()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "78",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "20",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "20",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "20",
                        "contact_desc" => "0850000000",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "20",
                        "contact_desc" => "rijky@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnIdentityIdNumberSudahDigunakan()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "78",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "56",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "42",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "77",
                        "contact_desc" => "0850000000",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "78",
                        "contact_desc" => "rijky@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );



        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnNomorHandphoneSudahDigunakan()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "78",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "55",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "42",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "77",
                        "contact_desc" => "085871792242",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "78",
                        "contact_desc" => "rijky@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnEmailNumberSudahDigunakan()
    {

        $param = array(
            "data" => array(
                "customer" => array(
                    "customer_id" => "78",
                    "customer_name" => "Rijky Sallaffudin Test",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-04-19",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_id" => "55",
                        "identity_desc" => "3219999999",
                        "identity_type" => "2",
                        "end_date" => "2090-08-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_id" => "42",
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri update",
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
                        "source" => "PGN",
                        "end_dtm" => ""
                    )
                ),
                "contact" => array(
                    array(
                        "contact_id" => "77",
                        "contact_desc" => "0850000000",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_id" => "78",
                        "contact_desc" => "budiana@neuronworks.com",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenUserLain,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

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
                "customer" => array(
                    "customer_id" => "",
                    "customer_name" => "",
                    "birth_place" => "",
                    "birth_date" => "",
                    "occupation" => "",
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
                ),
                "identity" => array(
                    array(
                        "identity_desc" => "",
                        "identity_type" => "",
                        "end_date" => "",
                        "status" => "",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
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
                ),
                "contact" => array(
                    array(
                        "contact_desc" => "",
                        "contact_type" => "",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_desc" => "",
                        "contact_type" => "",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenUserLain,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

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
                "customer" => array(
                    "customer_id" => "73",
                    "customer_name" => "Rijky Sallaffudin Abdul Haque",
                    "birth_place" => "bandung",
                    "birth_date" => "1990-11-28",
                    "occupation" => "Karyawan Swasta",
                    "gender" => "Laki-Laki",
                    "blood_type" => "A",
                    "mother_name" => "",
                    "last_education" => "",
                    "customer_alias" => "",
                    "religion" => "Islam",
                    "customer_category" => "PERSONAL",
                    "source" => "1",
                    "cust_status" => "1",
                    "image_profile" => "base 64 string",
                    "image_signature" => "base 64 string"
                ),
                "identity" => array(
                    array(
                        "identity_desc" => "32170111111001",
                        "identity_type" => "1",
                        "end_date" => "2090-01-28",
                        "status" => "1",
                        "source" => "PGN",
                        "document_image" => "base64 string"
                    )
                ),
                "address" => array(
                    array(
                        "addr_type" => "1",
                        "addr_desc" => "Griya hamdan asri",
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
                ),
                "contact" => array(
                    array(
                        "contact_desc" => "085871792241",
                        "contact_type" => "5",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    ),
                    array(
                        "contact_desc" => "budiana@neuronworks.co.id",
                        "contact_type" => "4",
                        "contact_priority" => "0",
                        "contact_name" => "",
                        "contact_status" => "1",
                        "sources" => "PGN"
                    )
                )
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
            // 'Accept' => 'application/json',
            // 'Content-Type' => 'application/json',
        ])
            ->put('/api/composite/update-customer', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
