<?php

namespace Tests\Feature\UnitTestCustomerProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitTestDeleteIdentityTest extends TestCase
{

    public function testBerhasilMelakukanDeleteDocument()
    {

        $param = array(
            "data" => array(
                "id" => "321701112224333"

            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/delete-document', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }
    public function testGagalDeleteDocumentMandatoryFieldRequired()
    {

        $param = array(
            "data" => array(
                "id" => ""

            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/delete-document', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
    public function testGagalMelakukanDeleteDocumentDenganInformasiInvalidToken()
    {

        $param = array(
            "data" => array(
                "id" => "321701112224333"

            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid,
        ])
            ->post('/api/identity/delete-document', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
    public function testGagalMelakukanDeleteDocumentDenganInformasiNotFound()
    {

        $param = array(
            "data" => array(
                "id" => "85"

            )
        );


        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token,
        ])
            ->post('/api/identity/delete-document', $param);

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
