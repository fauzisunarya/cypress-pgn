<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestUpdateIdentityTest extends TestCase
{

    public function testBehasilUpdateIdentity()
    {

        $param = array(
            "data" => array(
                "identity_id" => "42",
                "identity_desc" => "321701111144101Update",
                "identity_type" => "1",
                "end_date" => "2090-02-19",
                "source" => "PGN",
                "status" => "1",
                "document_image" => "base64 string"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/update', $param);

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
                "identity_id" => "99",
                "identity_desc" => "321701111144101Update",
                "identity_type" => "1",
                "end_date" => "2090-02-19",
                "source" => "PGN",
                "status" => "1",
                "document_image" => "base64 string"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnIdentitySudahDigunaka()
    {

        $param = array(
            "data" => array(
                "identity_id" => "41",
                "identity_desc" => "321701111144101Update",
                "identity_type" => "1",
                "end_date" => "2090-02-19",
                "source" => "PGN",
                "status" => "1",
                "document_image" => "base64 string"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/update', $param);

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
                "identity_id" => "",
                "identity_desc" => "",
                "identity_type" => "",
                "end_date" => "",
                "source" => "",
                "status" => "",
                "document_image" => "base64 string"
            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/update', $param);

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
                "identity_id" => "41",
                "identity_desc" => "321701111144101Update",
                "identity_type" => "1",
                "end_date" => "2090-02-19",
                "source" => "PGN",
                "status" => "1",
                "document_image" => "base64 string"
            )
        );

        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/identity/update', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
