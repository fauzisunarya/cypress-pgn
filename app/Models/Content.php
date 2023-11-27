<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Faker\Factory as Faker;
use App\Helper\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Content extends Model
{
    use HasApiTokens, HasFactory;
    
    public $table = 'cms.content';
    public $timestamps = false;

    public $fillable = [
        'id',
        'name',
        'start_date',
        'end_date',
        'status',
        'language',
        'module',
        'summary',
        'format',
        'create_dtm',
        'update_dtm',
        'create_by',
        'category_id',
    ];
    
    /* public function loadInfo($data, $bearerToken)
    {
        $url = 'http://127.0.0.1:8000/api/account/dummy-api';
        
        $param = array(
            'data' => array(
                'nomor_pelanggan' => $data['nomor_pelanggan']
            )
        );
        print_r($param);
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$bearerToken])->timeout(100)->post($url,$param);

        $content = $response->body();
        $decode = json_decode($content);
        return $decode;
    } */
    
    
    function createData($dataCustomer){
        
        $customerExist = Customer::where('nomor_pelanggan', $dataCustomer['nomor_pelanggan'])->first();
        if ($customerExist) {
            
            $createCustomer = [
                "first_name" => $dataCustomer['name'],
                "nomor_pelanggan" => $dataCustomer['noref'],
                "cust_status" => 1,
                "create_dtm" => Carbon::now(),
                "birth_place" => @$dataCustomer['birth_place'],
                "birth_date" => @$dataCustomer['birth_date'],
                "occupation" => @$dataCustomer['occupation'],
                "gender" => @$dataCustomer['gender'],
                "blood_type" => @$dataCustomer['blood_type'],
                "mother_name" => @$dataCustomer['mother_name'],
                "last_education" => @$dataCustomer['last_education'],
                "customer_alias" => @$dataCustomer['customer_alias'],
                "religion" => @$dataCustomer['religion'],
                "customer_category" => @$dataCustomer['customer_category'],
                "source" => @$dataCustomer['source'],
                "image_profile" => '',
                "image_signature" => '',
                "last_name" => @$dataCustomer['last_name']
            ];
            
            $dataCreate = Customer::updateOrCreate(['nomor_pelanggan' => $dataCustomer['nomor_pelanggan']],$createCustomer);
            //address
            Address::updateOrCreate(['cust_id' => $dataCreate->id, 'addr_type' => $dataCustomer['address']['addr_type']],
                [
                'addr_type' => $dataCustomer['address']['addr_type'],
                'addr_desc' => $dataCustomer['address']['addr_desc'],
                'country' => $dataCustomer['address']['country'],
                'house_no' => $dataCustomer['address']['house_no'],
                'cust_id' => $dataCreate->id,
                'rt' => $dataCustomer['address']['rt'],
                'rw' => $dataCustomer['address']['rw'],
                'latitude' => $dataCustomer['address']['latitude'],
                'longitude' => $dataCustomer['address']['longitude'],
                'street_name' => $dataCustomer['address']['street_name'],
                'area_name' => $dataCustomer['address']['area_name'],
                'district_name' => $dataCustomer['address']['district_name'],
                'district_id' => $dataCustomer['address']['district_id'],
                'city_name' => $dataCustomer['address']['city_name'],
                'city_id' => $dataCustomer['address']['city_id'],
                'province' => $dataCustomer['address']['province'],
                'province_id' => $dataCustomer['address']['province_id'],
                'postal_code' => $dataCustomer['address']['postal_code'],
                'update_dtm' => Carbon::now(),
                'update_by' => $dataCustomer['address']['update_by'],
                'source' => $dataCustomer['address']['source'],
                'ref_id' => $dataCustomer['address']['ref_id']
            ]);
            
            //nik
            Identity::updateOrCreate(['cust_id' => $dataCreate->id, 'identity_type' => 1],
            [
                'cust_id' => $dataCreate->id,
                'identity_desc' => $dataCustomer['nik'],
                'identity_type' => 1,
                'end_date' => null,
                'source' => 'PGN',
                'status' => 1,
                'document_image' => $dataCustomer['nik_path'],
            ]);
            
            //npwp
            Identity::updateOrCreate(['cust_id' => $dataCreate->id, 'identity_type' => 5],
            [
                'cust_id' => $dataCreate->id,
                'identity_desc' => $dataCustomer['npwp'],
                'identity_type' => 5,
                'end_date' => null,
                'source' => 'PGN',
                'status' => 1,
                'document_image' => $dataCustomer['npwp_path'],
            ]);
            
            //contact email
            Contact::updateOrCreate(['cust_id' => $dataCreate->id, 'contact_type' => $dataCustomer['contact'][0]['contact_type']],
            [
                'cust_id' => $dataCreate->id,
                'contact_desc' => $dataCustomer['contact'][0]['contact_desc'],
                'contact_type' => $dataCustomer['contact'][0]['contact_type'],
                'contact_priority' => $dataCustomer['contact'][0]['contact_priority'],
                'contact_name' => $dataCustomer['contact'][0]['contact_name'],
                'contact_status' => $dataCustomer['contact'][0]['contact_status'],
                'sources' => $dataCustomer['contact'][0]['sources']
            ]);
            
            //contact nomor hp
            Contact::updateOrCreate(['cust_id' => $dataCreate->id, 'contact_type' => $dataCustomer['contact'][1]['contact_type']],
            [
                'cust_id' => $dataCreate->id,
                'contact_desc' => $dataCustomer['contact'][1]['contact_desc'],
                'contact_type' => $dataCustomer['contact'][1]['contact_type'],
                'contact_priority' => $dataCustomer['contact'][1]['contact_priority'],
                'contact_name' => $dataCustomer['contact'][1]['contact_name'],
                'contact_status' => $dataCustomer['contact'][1]['contact_status'],
                'sources' => $dataCustomer['contact'][1]['sources']
            ]);
            
            //asset
            Asset::updateOrCreate(['customer_id' => $dataCreate->id],
            [
                'customer_id' => $dataCreate->id,
                'nomor_pelanggan' => $dataCustomer['asset']['nomor_pelanggan'],
                'segment' => $dataCustomer['asset']['segment'],
                'daya_listrik' => $dataCustomer['asset']['daya_listrik'],
                'nomor_meteran' => $dataCustomer['asset']['nomor_meteran'],
                'product' => $dataCustomer['asset']['product'],
                'region' => $dataCustomer['asset']['region'],
                'asset_status' => $dataCustomer['asset']['asset_status'],
                'create_dtm' => $dataCustomer['asset']['create_dtm'],
                'create_by' => $dataCustomer['asset']['create_by'],
                'update_dtm' => $dataCustomer['asset']['update_dtm'],
                'update_by' => $dataCustomer['asset']['update_by'],
                'segment_type' => $dataCustomer['asset']['segment_type']
            ]);
        } else {
            $dataCreate = Customer::create([
                "first_name" => $dataCustomer['name'],
                "nomor_pelanggan" => $dataCustomer['noref'],
                "cust_status" => 1,
                "create_dtm" => Carbon::now(),
                "birth_place" => @$dataCustomer['birth_place'],
                "birth_date" => @$dataCustomer['birth_date'],
                "occupation" => @$dataCustomer['occupation'],
                "gender" => @$dataCustomer['gender'],
                "blood_type" => @$dataCustomer['blood_type'],
                "mother_name" => @$dataCustomer['mother_name'],
                "last_education" => @$dataCustomer['last_education'],
                "customer_alias" => @$dataCustomer['customer_alias'],
                "religion" => @$dataCustomer['religion'],
                "customer_category" => @$dataCustomer['customer_category'],
                "source" => @$dataCustomer['source'],
                "image_profile" => '',
                "image_signature" => '',
                "last_name" => @$dataCustomer['last_name']
            ]);
            
            
            //address
            Address::create([
                'addr_type' => $dataCustomer['address']['addr_type'],
                'addr_desc' => $dataCustomer['address']['addr_desc'],
                'country' => $dataCustomer['address']['country'],
                'house_no' => $dataCustomer['address']['house_no'],
                'cust_id' => $dataCreate->id,
                'rt' => $dataCustomer['address']['rt'],
                'rw' => $dataCustomer['address']['rw'],
                'latitude' => $dataCustomer['address']['latitude'],
                'longitude' => $dataCustomer['address']['longitude'],
                'street_name' => $dataCustomer['address']['street_name'],
                'area_name' => $dataCustomer['address']['area_name'],
                'district_name' => $dataCustomer['address']['district_name'],
                'district_id' => $dataCustomer['address']['district_id'],
                'city_name' => $dataCustomer['address']['city_name'],
                'city_id' => $dataCustomer['address']['city_id'],
                'province' => $dataCustomer['address']['province'],
                'province_id' => $dataCustomer['address']['province_id'],
                'postal_code' => $dataCustomer['address']['postal_code'],
                'update_dtm' => Carbon::now(),
                'update_by' => $dataCustomer['address']['update_by'],
                'source' => $dataCustomer['address']['source'],
                'ref_id' => $dataCustomer['address']['ref_id']
            ]);
            
            //nik
            Identity::create([
                'cust_id' => $dataCreate->id,
                'identity_desc' => $dataCustomer['nik'],
                'identity_type' => 1,
                'end_date' => null,
                'source' => 'PGN',
                'status' => 1,
                'document_image' => $dataCustomer['nik_path'],
            ]);
            
            //npwp
            Identity::create([
                'cust_id' => $dataCreate->id,
                'identity_desc' => $dataCustomer['npwp'],
                'identity_type' => 5,
                'end_date' => null,
                'source' => 'PGN',
                'status' => 1,
                'document_image' => $dataCustomer['npwp_path'],
            ]);
            
            //contact email
            Contact::create([
                'cust_id' => $dataCreate->id,
                'contact_desc' => $dataCustomer['contact'][0]['contact_desc'],
                'contact_type' => $dataCustomer['contact'][0]['contact_type'],
                'contact_priority' => $dataCustomer['contact'][0]['contact_priority'],
                'contact_name' => $dataCustomer['contact'][0]['contact_name'],
                'contact_status' => $dataCustomer['contact'][0]['contact_status'],
                'sources' => $dataCustomer['contact'][0]['sources']
            ]);
            
            //contact nomor hp
            Contact::create([
                'cust_id' => $dataCreate->id,
                'contact_desc' => $dataCustomer['contact'][1]['contact_desc'],
                'contact_type' => $dataCustomer['contact'][1]['contact_type'],
                'contact_priority' => $dataCustomer['contact'][1]['contact_priority'],
                'contact_name' => $dataCustomer['contact'][1]['contact_name'],
                'contact_status' => $dataCustomer['contact'][1]['contact_status'],
                'sources' => $dataCustomer['contact'][1]['sources']
            ]);
            
            //asset
            Asset::create([
                'customer_id' => $dataCreate->id,
                'nomor_pelanggan' => $dataCustomer['asset']['nomor_pelanggan'],
                'segment' => $dataCustomer['asset']['segment'],
                'daya_listrik' => $dataCustomer['asset']['daya_listrik'],
                'nomor_meteran' => $dataCustomer['asset']['nomor_meteran'],
                'product' => $dataCustomer['asset']['product'],
                'region' => $dataCustomer['asset']['region'],
                'asset_status' => $dataCustomer['asset']['asset_status'],
                'create_dtm' => $dataCustomer['asset']['create_dtm'],
                'create_by' => $dataCustomer['asset']['create_by'],
                'update_dtm' => $dataCustomer['asset']['update_dtm'],
                'update_by' => $dataCustomer['asset']['update_by'],
                'segment_type' => $dataCustomer['asset']['segment_type']
            ]);
        }
        
        return $dataCreate;
        
    }
}
