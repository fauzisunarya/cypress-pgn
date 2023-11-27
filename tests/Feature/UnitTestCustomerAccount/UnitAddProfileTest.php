<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitAddProfileTest extends TestCase
{

    public function testBehasilMelakukanRequestAddProfile()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "15",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "081000000000",
                "district" => "bandung",
                "serial_number" => "000223212",
                "last_payment" => "199000",
                "type" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

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
                "nomor_pelanggan" => "15",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "081000000000",
                "district" => "bandung",
                "serial_number" => "000223212",
                "last_payment" => "199000",
                "type" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalRequestDenganInformasiMandatoryFieldRequiredTypeSatu()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "15",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "",
                "district" => "",
                "serial_number" => "000223212",
                "last_payment" => "199000",
                "type" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalRequestDenganInformasiMandatoryFieldRequiredTypeDua()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "15",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "081000000000",
                "district" => "bandung",
                "serial_number" => "",
                "last_payment" => "",
                "type" => "2"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

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
                "nomor_pelanggan" => "123000",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "081000000000",
                "district" => "bandung",
                "serial_number" => "000223212",
                "last_payment" => "199000",
                "type" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    // public function testGagalRequestDenganInformasiaddAccountDalamReview()
    // {
    //     $param = array(
    //         "data" => array(
    //             "nomor_pelanggan" => "15",
    //             "name" => "Rijky Sallaffudin Abdul Haque",
    //             "identity" => "234567875678654",
    //             "phone_number" => "081000000000",
    //             "district" => "bandung",
    //             "serial_number" => "000223212",
    //             "last_payment" => "199000",
    //             "type" => "1"
    //         )
    //     );
    //     /** get api Education */
    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $this->_token
    //     ])
    //         ->post('/api/account/add', $param);

    //     /** mengambil response body dari get api Education */
    //     $content = $response->decodeResponseJson();
    //     $code = $content['code'];

    //     /** ekspetasi response api dengan testcase ini */
    //     $this->assertEquals($code, 0);
    // }

    public function testGagalAddProfileDenganInformasiInvalidToken()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "15",
                "name" => "Rijky Sallaffudin Abdul Haque",
                "identity" => "234567875678654",
                "phone_number" => "081000000000",
                "district" => "bandung",
                "serial_number" => "000223212",
                "last_payment" => "199000",
                "type" => "1"
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/account/add', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalAddProfileDenganInformasiMandatoryFieldRequired()
    {
        $param = array(
            "data" => array(
                "nomor_pelanggan" => "",
                "name" => "",
                "identity" => "",
                "phone_number" => "",
                "district" => "",
                "serial_number" => "",
                "last_payment" => "",
                "type" => ""
            )
        );
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/account/add', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
