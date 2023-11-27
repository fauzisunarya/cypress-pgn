<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestCreateContactTest extends TestCase
{

    public function testBehasilCreateContact()
    {

        $param = array(
            "data" => array(
                "customer_id" => "60",
                "contact_desc" => "0859922092241",
                "contact_type" => "5",
                "contact_priority" => "0",
                "contact_name" => "",
                "contact_status" => "1",
                "sources" => "PGN"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/contact/create', $param);

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
                "customer_id" => "78",
                "contact_desc" => "085871792241",
                "contact_type" => "5",
                "contact_priority" => "0",
                "contact_name" => "",
                "contact_status" => "1",
                "sources" => "PGN"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/contact/create', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnContactSudahDigunakan()
    {

        $param = array(
            "data" => array(
                "customer_id" => "60",
                "contact_desc" => "085871792241",
                "contact_type" => "5",
                "contact_priority" => "0",
                "contact_name" => "",
                "contact_status" => "1",
                "sources" => "PGN"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/contact/create', $param);

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
                "customer_id" => "",
                "contact_desc" => "",
                "contact_type" => "",
                "contact_priority" => "0",
                "contact_name" => "joko",
                "contact_status" => "",
                "sources" => "PGN"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/contact/create', $param);

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
                "customer_id" => "60",
                "contact_desc" => "085871792241",
                "contact_type" => "5",
                "contact_priority" => "0",
                "contact_name" => "",
                "contact_status" => "1",
                "sources" => "PGN"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/contact/create', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
