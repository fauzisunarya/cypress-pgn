<?php

namespace Tests\Feature\UnitTestInvoice;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnitInvoiceListTest extends TestCase
{
    
    public function testBerhasilMemunculkanInvoice()
    {
    
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/invoice/list');

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertEquals($code, 0);
    }

    public function testGagalReturnInvoiceListTidakDitemukan()
    {
    
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/invoice/list');

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnUnauthorized()
    {
    
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/invoice/list');

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnMandatoryFieldRequired()
    {
    
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_token
        ])
            ->post('/api/invoice/list');

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }

    public function testGagalReturnInformasiInvalidToken()
    {
    
        /** get api Education */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->_tokenInvalid
        ])
            ->post('/api/invoice/list');

        /** mengambil response body dari get api Education */
        $content = $response->decodeResponseJson();
        $code = $content['code'];

        /** ekspetasi response api dengan testcase ini */
        $this->assertNotEquals($code, 0);
    }
}
