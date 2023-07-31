<?php

namespace Drupal\media_upload\Helper;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Drupal\Component\Utility\Html;

class LandingForm {

  private function get_form_scheme($scheme_id){
    $scheme = '';
    switch ($scheme_id) {
      case 'psb_retail_ebis':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya"}}';
        break;

      case 'psb_retail_ebis_map':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"coordinate","label":"Coordinate","field":"coordinate","type":"text","placeholder":"Select on map","required":true,"options":[],"size":"full","otherfield":{"name":"custAddressText","label":"Alamat","placeholder":"Alamat anda","show":true}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya"}}';
        break;

      case 'psb_retail_ebis_map_file':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"coordinate","label":"Coordinate","field":"coordinate","type":"text","placeholder":"Select on map","required":true,"options":[],"size":"full","otherfield":{"name":"custAddressText","label":"Alamat","placeholder":"Alamat anda","show":true}},{"name":"ktp","label":"KTP","field":"file","type":"ktp","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"selfie","label":"Foto selfie","field":"file","type":"selfie","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"signature","label":"Signature","field":"signature","type":"text","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya. Draft contract bisa dilihat pada email dan link di bawah ini"}}';
        break;

      case 'form_basic':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya"}}';
        break;

      case 'form_basic_map':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"coordinate","label":"Coordinate","field":"coordinate","type":"text","placeholder":"Select on map","required":true,"options":[],"size":"full","otherfield":{"name":"custAddressText","label":"Alamat","placeholder":"Alamat anda","show":true}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya"}}';
        break;

      case 'form_basic_map_file':
        $scheme = '{"fields":[{"label":"Paket","field":"package","type":"text","placeholder":"Pilih paket","required":true,"options":[],"size":"full","name":"packageId","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"label":"User referral code","field":"input","type":"text","placeholder":"Kode referral","required":true,"options":[],"size":"full","name":"userCode","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"coordinate","label":"Coordinate","field":"coordinate","type":"text","placeholder":"Select on map","required":true,"options":[],"size":"full","otherfield":{"name":"custAddressText","label":"Alamat","placeholder":"Alamat anda","show":true}},{"name":"ktp","label":"KTP","field":"file","type":"ktp","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"selfie","label":"Foto selfie","field":"file","type":"selfie","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"signature","label":"Signature","field":"signature","type":"text","placeholder":"Teks sementara","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Terima kasih, silahkan cek email anda untuk info selanjutnya. Draft contract bisa dilihat pada email dan link di bawah ini"}}';
        break;

      case 'mo_mydita_basic':
        $scheme = '{"fields":[{"name":"custName","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custEmail","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"custPhone","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"nd","label":"Nomor Internet","field":"input","type":"text","placeholder":"Nomor Internet","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"addon","label":"Addon","field":"package","type":"text","placeholder":"Pilih Addon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim","position":"right","url":"mydita"},"actions":{"position":"cover","message":"Halo, terima kasih telah menghubungi kami, kami akan mengontak anda kembali"}}';
        break;
      
      default:
        $scheme = '{"fields":[{"name":"nama","label":"Nama Lengkap","field":"input","type":"text","placeholder":"Nama lengkap anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"email","label":"Alamat Email","field":"input","type":"email","placeholder":"Alamat email anda","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"no_telepon","label":"Nomor Telepon","field":"input","type":"text","placeholder":"Nomor Telepon","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}},{"name":"pesan","label":"Pesan","field":"textarea","type":"text","placeholder":"Masukkan pesan anda disini","required":true,"options":[],"size":"full","otherfield":{"name":"","label":"Label","placeholder":"Text sementara","show":false}}],"submit":{"label":"Kirim Pesan","position":"right","url":"cms"},"actions":{"position":"top","message":"Halo, terima kasih telah menghubungi kami, kami akan mengontak anda kembali"}}';
        break;
    }
    return $scheme;
  }

  /**
   * $landing_type can be filled with :
   * "ao/psb retail" , "ao/psb retail+ebis" , "mo" , "other"
   */
  public function get_default_forms($form_id = '', $landing_type = ''){

    $landing_type = strtolower($landing_type);

    $forms = [
      [
        'form_id'     => 'psb-retail-ebis',
        'form_name'   => 'PSB retail ebis',
        'form_scheme' => $this->get_form_scheme('psb_retail_ebis')
      ],
      [
        'form_id'     => 'psb-retail-ebis-map',
        'form_name'   => 'PSB retail ebis + map',
        'form_scheme' => $this->get_form_scheme('psb_retail_ebis_map')
      ],
      [
        'form_id'     => 'psb-retail-ebis-map-file',
        'form_name'   => 'PSB retail ebis + map + file',
        'form_scheme' => $this->get_form_scheme('psb_retail_ebis_map_file')
      ],
      [
        'form_id'     => 'psb-mydita-basic',
        'form_name'   => 'PSB basic',
        'form_scheme' => $this->get_form_scheme('form_basic')
      ],
      [
        'form_id'     => 'psb-mydita-basic-map',
        'form_name'   => 'PSB basic + map',
        'form_scheme' => $this->get_form_scheme('form_basic_map')
      ],
      [
        'form_id'     => 'psb-mydita-basic-map-file',
        'form_name'   => 'PSB basic + map + file',
        'form_scheme' => $this->get_form_scheme('form_basic_map_file')
      ],
      [
        'form_id'     => 'mo-mydita-basic',
        'form_name'   => 'MO basic',
        'form_scheme' => $this->get_form_scheme('mo_mydita_basic')
      ],
    ];

    // filter allowed form for based on landing page type
    if (!empty($landing_type)) {
      $allowed_landing_form = $this->get_allowed_landing_form_id();
      if (empty( $allowed_landing_form[ $landing_type ] )) {
        return []; //error invalid landing type
      }
      $allowed_ids = $allowed_landing_form[ $landing_type ];
      $forms = array_filter($forms, function($form) use($allowed_ids){
        if (in_array($form['form_id'], $allowed_ids)) {
          return true;
        }
        return false;
      });
    }

    // dd($form_id);
    if (!empty($form_id)) {
      $forms_filtered = array_filter($forms, function($form) use($form_id){
        if ($form['form_id'] === $form_id) {
          return true;
        }
        return false;
      });

      if (count($forms_filtered) === 1) {
        return array_values($forms_filtered)[0];
      }
      
      return [];
    }

    return !empty($landing_type) ? array_values($forms) : $forms; //fix index start from 0 and so on
  }

  public function get_allowed_landing_form_id() {
    return [
      "ao/psb retail" => ["psb-mydita-basic", "psb-mydita-basic-map", "psb-mydita-basic-map-file"],
      "ao/psb retail+ebis" => ["psb-retail-ebis", "psb-retail-ebis-map", "psb-retail-ebis-map-file"],
      "mo" => ["mo-mydita-basic"],
      "other" => []
    ];
  }

  public function get_random_string(){
    return str_shuffle(MD5(microtime()));
  }

  public function send_post_to_mydita($form_id=null, $form_scheme=null, $landing=null){
    if (!$form_id || !$form_scheme || !$landing) {
      return new JsonResponse('bad request, invalid submit request', 400);
    }

    if (isset($_POST['nd'])) {
      // mo = modified order (purchase other item, nd = internet number)
      return $this->mydita_mo($form_scheme, $landing);
    }
    else{
      // psb = pasang baru (new order, nd is not exist because this is new order)
      $args = [];
      if (in_array($form_id, ["psb-retail-ebis", "psb-retail-ebis-map", "psb-retail-ebis-map-file"])) {
        $args['subTypeId'] = 2;
      }
      return $this->mydita_psb($form_scheme, $landing, $args);
    }
  }

  private function mydita_psb($form_scheme, $landing, $args=[]){

    $post = array_map(fn($val)=>trim(Html::escape($val)), $_POST);
    unset($_POST);

    $fields = $form_scheme['fields'];

    $data = []; $datafiles = [];
    foreach ($post as $name => $value) {
      foreach ($fields as $field) {
        if ($name===$field['name']) {
          switch ($field['field']) {

            case 'coordinate':
              $latlng = explode(',', $value);
              $data['latitude']  = $latlng[0];
              $data['longitude'] = $latlng[1];

              if ($field['otherfield']['show']) {
                $data[$field['otherfield']['name']] = $post[$field['otherfield']['name']]; //this is address
              }
              break;

            case 'package':
              $package = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_pkt_package_id'=>$value]);
              $package = current($package);
              if ($package && $package->bundle()==='paket') {
                $data['packageId'] = $value;
                $data['custPackage'] = $package->label();
              }
              break;
            
            case 'signature':
              $datafiles['p_ttd_location'] = explode(';base64,',$value)[1];
              break;

            case 'file':
              if (in_array($field['type'], ['ktp', 'selfie'])) {
                $datafiles["p_{$field['type']}_location"] = explode(';base64,',$value)[1];
              }
              // else{
              //   $datafiles[$name] = $value;
              // }
              break;
            
            default:
              $data[$name] = $value;
              break;
          }

        }
      }
    }

    // if user open landing from amanda broadcast or exist partnerId
    $userCode = isset($data['userCode']) ? $data['userCode'] : '';
    $partnerId = !empty($post['partnerId']) ? $post['partnerId'] : '';
    $amdCode = '';
    if (!empty($post['amdcode'])) {
      preg_match( "/^AMD\d\d\d\d\d\d\d\d$/i", $post['amdcode'], $amanda );
      if ( count($amanda) === 1 ) {
        $amdCode = strtoupper($amanda[0]);
      }
    }
    $data['userCode'] = "{$amdCode}-{$userCode}-{$partnerId}";

    unset($post);

    $uploadPaperless = !empty($datafiles) && !empty($datafiles['p_ktp_location']) && !empty($datafiles['p_selfie_location'])  && !empty($datafiles['p_ttd_location']);

    if (!empty($datafiles['p_ttd_location']) && (empty($datafiles['p_ktp_location']) || empty($datafiles['p_selfie_location'])) ) {
      return new JsonResponse(['status'=> 400, 'message'=> 'File upload error'], 400);
    }

    $data['loadPackage'] = true;
    $data['sourceOrder'] = $landing->field_lan_channel_id->getString();
    $data['createContract'] = $uploadPaperless ? false : true;

    $data = array_merge($data, $args); // merge with additional parameter, if exist

    /**
     * Flow : 
     * 1. Send data like name, email, etc (except file) to mydita => get track_id
     * 2. If have file, send file to paperless using track_id as identifier
     * 3. If success send to paperless, send callback to mydita to inform that contract paperless has been created
     * 4. If there is no file, ignore step 2 & 3 
     */

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200){
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // create order to mydita
      if (!empty($accessToken)) {
        $responseData  = $client->post('gateway/telkom-inboxAgentDigital/1.0/createOrder', [
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            'Authorization' => $accessToken
          ],
          'body' => json_encode([
            "data" => $data
          ])
        ]);

        $response = json_decode($responseData->getBody());

        // add to log
        $logMyditaPaperless = Node::create([
          'type' => 'log_mydita_paperless',
          'title' => 'Log',
          'field_lmp_create_order_request' => json_encode(["data" => $data]),
          'field_lmp_create_order_response' => (string) $responseData->getBody()
        ]);
        $logMyditaPaperless->save();
        
        if ($responseData->getStatusCode() == 200 && $response->code === 0){
            $track_id = $response->data[0]->track_id;

          // send to paperless if file exists
          if ($uploadPaperless) {
            // prepare variable to send to paperless
            $bodyRequestPaperless = array (
              'guid' => 0,
              'code' => 0,
              'data' => array (
                'p_status_order' => 'OPEN',
                'p_segment' => 'DCS',
                'p_transaction_type' => 'AO',
                'p_sc_id' => '',
                'p_track_id' => $track_id,
                'p_nd_inet' => NULL,
                'p_nd_voice' => NULL,
                'p_source' => $data['sourceOrder'],
                'p_ncli' => '',
                'p_customer_name' => $data['custName'],
                'p_customer_type' => 'Residensial',
                'p_customer_identity_type' => '',
                'p_customer_identity_num' => '',
                'p_customer_npwp' => '',
                'p_customer_contact' => $data['custPhone'],
                'p_customer_contact_alternative' => '',
                'p_customer_email' => $data['custEmail'],
                'p_customer_birth_place' => NULL,
                'p_customer_birth_date' => NULL,
                'p_customer_gender' => '',
                'p_customer_address' => $data['custAddressText'],
                'p_customer_post_code' => '',
                'p_customer_city' => '',
                'p_customer_official_id' => '',
                'p_installation_address' => '',
                'p_division' => '',
                'p_bill_address' => '',
                'p_billing_confirm_type' => '',
                'p_billing_type' => '',
                'p_billing_card_type' => '',
                'p_billing_card_num' => '',
                'p_bill_card_validity_date' => '',
                'p_bank_type' => '',
                'p_regional' => '',
                'p_witel' => '',
                'p_sto' => '',
                'p_va_date' => '',
                'p_speed' => 0,
                'p_channel' => '',
                'p_xs2' => '',
                'p_xs3' => '',
                'p_indihome_price' => 0,
                'p_psb_price' => 0,
                'p_rent_price' => 0,
                'p_additional_price' => 0,
                'p_total_price' => 0,
                'p_paket' => 0,
                'p_stb' => 0,
                'p_added_item_price' => 0,
                'p_deposit' => 0,
                'p_product_service' => '',
                'p_link_type' => 'SMS_EMAIL',
                'p_send_link' => 'Y',
                'p_package_detail' => array (),
                'p_agent_contact' => '',
                'p_agent_email' => '',
                'p_provinsi' => '',
                'p_xs1' => 'Y',
                'p_send_email_draft' => true,
                'p_additional_type' => '',
                'p_installation_price' => 0,
                'p_biaya_ont' => '0',
                'p_biaya_stb' => '0',
                'p_cpe_price' => 0,
                'p_send_landing' => '',
                'p_lampiran_1' => '',
                'p_lampiran_1_title' => '',
                'p_lampiran_2' => '',
                'p_lampiran_2_title' => '',
                'p_lampiran_3' => '',
                'p_lampiran_3_title' => '',
                'p_ktp_location' => substr($datafiles['p_ktp_location'], 50, 15) . "..." ,
                'p_selfie_location' => substr($datafiles['p_selfie_location'], 50, 15) . "..." ,
                'p_ttd_location' => substr($datafiles['p_ttd_location'], 50, 15) . "..." 
              ),
            );

            // add to log
            $logMyditaPaperless->set('field_lmp_paperless_request', json_encode($bodyRequestPaperless));
            $logMyditaPaperless->save();

            $bodyRequestPaperless['data']['p_ktp_location'] = $datafiles['p_ktp_location'];
            $bodyRequestPaperless['data']['p_selfie_location'] = $datafiles['p_selfie_location'];
            $bodyRequestPaperless['data']['p_ttd_location'] = $datafiles['p_ttd_location'];
            unset($datafiles);
            
            // send to paperless
            $responseData  = $client->post('gateway/telkom-mycx-order/1.0/sendOrderPaperless', [
              'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'Authorization' => $accessToken
              ],
              'body' => json_encode($bodyRequestPaperless)
            ]);
            unset($bodyRequestPaperless);
    
            $response = json_decode($responseData->getBody());
    
            // add to log
            $logMyditaPaperless->set('field_lmp_paperless_response', (string) $responseData->getBody() );
            $logMyditaPaperless->save();

            if ($responseData->getStatusCode() == 200 && $response->data->code === 0){
              
              $draftContract = $response->data->data->baseurl;

              // send callback to mydita
              $callbackRequest = array (
                'guid' => '',
                'code' => '1',
                'data' => array (
                  'trackId' => $track_id,
                  'command' => 'uploadCallback',
                  'status' => 'UPLOAD',
                  'uploadTime' => date("Y-m-d H:i:s"),
                  'source' => $data['sourceOrder'],
                ),
              );

              // add to log
              $logMyditaPaperless->set('field_lmp_callback_request', json_encode($callbackRequest));
              $logMyditaPaperless->save();

              $responseData  = $client->post('gateway/telkom-inboxAgentDigital/1.0/modifyOrder', [
                'headers' => [
                  'Content-Type' => 'application/json',
                  'Accept'       => 'application/json',
                  'Authorization' => $accessToken
                ],
                'body' => json_encode(array (
                  'modifyOrderCodeRequest' => array (
                    'eaiHeader' => array (
                      'internalId' => '',
                      'externalId' => '',
                      'timestamp' => '',
                      'responseTimestamp' => '',
                    ),
                    'eaiBody' => $callbackRequest,
                  ),
                ))
              ]);
      
              $response = json_decode($responseData->getBody());

              // add to log
              $logMyditaPaperless->set('field_lmp_callback_response', (string) $responseData->getBody() );
              $logMyditaPaperless->save();

              if ($responseData->getStatusCode() == 200 && $response->ModifyOrderCodeResponse->eaiBody->code === 0){
                return new JsonResponse([
                  'status'=> 200, 
                  'message'=> "Order dengan track id <strong>{$track_id}</strong> berhasil dibuat, silahkan cek email anda untuk info selanjutnya. Draft contract bisa dilihat pada email dan link di bawah ini", 
                  'data'=> [
                     'draftContract'=> $draftContract
                  ]
                ], 200);
              }
              else{
                return new JsonResponse(['status'=> 400, 'message'=> 'Something error: failed send callback'], 400);
              }

            }
            else{
              // failed upload to paperless
              return new JsonResponse([
                'status'=> 400, 
                'message'=> 'Something error: failed upload to paperless'
              ], 400);
            }
          }
          else {
            // success submit to mydita (only mydita, not to paperless)
            return new JsonResponse([
              'status'=> 200, 
              'message'=> "Order dengan track id <strong>{$track_id}</strong> berhasil dibuat, silahkan cek email anda untuk info selanjutnya"
            ], 200);
          }
        }
        else{
          // failed send to mydita (create order)
          return new JsonResponse([
            'status'=> 400, 
            'message'=> !empty($response->info) ? "Something error: ".$response->info : 'Something error: failed create order'
          ], 400);
        }
      }

    }

    return new JsonResponse('bad request', 400);
  }

  private function mydita_mo($form_scheme, $landing){

    $post = array_map(fn($val)=>trim(Html::escape($val)), $_POST);
    unset($_POST);

    $fields = $form_scheme['fields'];

    $data = [];
    foreach ($post as $name => $value) {
      foreach ($fields as $field) {
        if ($name===$field['name']) {
          switch ($field['field']) {

            case 'package':
              $package = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_pkt_package_id'=>$value]);
              $package = current($package);
              if ($package && $package->bundle()==='paket') {
                $data['addons'] = [
                  [
                    'name' => $package->label(),
                    'code' => $value,
                    'offerId' => 10
                  ]
                ];
                // $data['custPackage'] = $package->label();
              }
              break;
            
            default:
              $data[$name] = $value;
              break;
          }

        }
      }
    }

    $data['sourceOrder'] = 'DMP';
    $data['createContract'] = false;
    $data['typeId'] = 2;
    $data['subTypeId'] = 6;
    $data['indihomeIndicator'] = "Indihome";
    $data['straightToSc'] = true;

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200){
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // create order to mydita
      if (!empty($accessToken)) {

        $responseData  = $client->post('gateway/telkom-inboxAgentDigital/1.0/createOrder', [
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            'Authorization' => $accessToken
          ],
          'body' => json_encode(['data'=>$data])
        ]);

        $response = json_decode($responseData->getBody());

        // add to log
        $logMyditaPaperless = Node::create([
          'type' => 'log_mydita_paperless',
          'title' => 'Log',
          'field_lmp_create_order_request' => json_encode(['data'=>$data]),
          'field_lmp_create_order_response' => json_encode($response)
        ]);
        $logMyditaPaperless->save();
        
        if ($responseData->getStatusCode() == 200 && $response->code === 0){
          return new JsonResponse(['status'=> 200, 'message'=> 'success'], 200);
        }
        else{
          return new JsonResponse(['status'=> 400, 'message'=> 'failed send to mydita'], 400);
        }
      }

    }

    return new JsonResponse('bad request', 400);

  }

}