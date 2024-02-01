<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Result;
use App\Models\Content;
use App\Models\Content\Header;
use App\Models\Content\Detail;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use AG\ElasticApmLaravel\Facades\ApmCollector;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContentController extends Controller {
    public function index(Request $request) {
        ApmCollector::startMeasure('content-list-span', 'login', 'measure', 'content list');
        $result = new Result();
        $post = $request->data;

        $request->validate([
            'data.order' => 'required',
            'data.start' => 'required',
            'data.length' => 'required',
        ]);

        // if (array_key_exists('grants', $request->user)) {
        //     if (!in_array(Content::LOADBY_GRANTS_LIST, $request->user['grants'])) {
        //         $result->code = 3;
        //         $result->info = __("Unauthorized");
        //         return response()->json($result, $result->status);
        //     }
        // }
        
        $search = strtolower($post['search']);
        
        $column = !empty($post['order']['column']) ? $post['order']['column'] : 'id';
        $dir = !empty($post['order']['dir']) ? $post['order']['dir'] : 'asc';
        $length = !empty($post['length']) ? $post['length'] : 5;

        $data = Content::select('content.*', 'content_status.status_name', 'content_category.category_name')
        ->join('cms.content_status', 'content.status', '=', 'content_status.id')
        ->join('cms.content_category', DB::raw('CAST(content.category_id AS INTEGER)'), '=', 'content_category.id');

        if (isset($search) && !empty($search)) {
            $data = $data->where('name', 'ILIKE', '%'.$search.'%')
            ->orWhere('content_status.status_name', 'ILIKE', '%'.$search.'%')
            ->orWhere('content_category.category_name', 'ILIKE', '%'.$search.'%');
        }
        
        $data = $data->orderBy($column, $dir)
        ->paginate($length);
        $offset = ($data->currentPage()* $data->perPage()) - $data->perPage();
        for ($i = 0; $i < $data->count();$i++) {
            $data[$i]->no = $i+$offset+1;
        }

        if (empty($data->items())) {
            $result->code = 1;
            $result->info = __("Data not found");
            $result->data = $data->items();
            return response()->json($result, $result->status);
        }

        $result->data = $data;

        ApmCollector::stopMeasure('content-list-span');
        return response()->json($result, $result->status);
    }

    function create(Request $request) {
        ApmCollector::startMeasure('content-create-span', 'login', 'measure', 'content create');
        $result = new Result();
        $request->validate([
            'data.name' => 'required',
            'data.category_id' => 'required',
            'data.language' => 'required',
            'data.module' => 'required',
            'data.format' => 'required',
            'data.start_date' => 'required',
            'data.content_body' => 'required',
        ]);

        $data = $request->data;
        $user = $request->user;

        // if (array_key_exists('grants', $request->user)) {
        //     if (!in_array(Content::LOADBY_GRANTS_CREATE, $request->user['grants'])) {
        //         $result->code = 3;
        //         $result->info = __("Unauthorized");
        //         return response()->json($result, $result->status);
        //     }
        // }

        try {
            DB::beginTransaction();
            $create = Content::create([
                'name' => @$data['name'],
                'start_date' => @$data['start_date'],
                'end_date' => @$data['end_date'],
                'status' => @$data['status'],
                'language' => @$data['language'],
                'module' => @$data['module'],
                'summary' => "",
                'format' => @$data['format'],
                'create_dtm' => Carbon::now(),
                'update_dtm' => null,
                'create_by' => $user['sub'],
                'category_id' => @$data['category_id'],
            ]);

            $create_id = $create->id;

            if ($data['content_body']) {
                $value = $data['content_body']['value'];
                foreach ($value as $key => $val) {
                    $img_banner = '';
                    if (isset($val['image_banner'])) {
                        if(strlen($val['image_banner']) <=3){
                            $img_banner = $val['image_banner'];
                        }else{
                            $img_banner = 'product/cms/header/'.$create_id.'/'.$key.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                            Storage::disk('minio')->put($img_banner, $val['image_banner']);
                        }
                    }

                    $img = '';
                    if (isset($val['header']['image'])) {
                        if(strlen($val['header']['image']) <=3){
                            $img = $val['header']['image'];
                        }else{
                            $img = 'product/cms/header/'.$create_id.'/'.$key.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                            Storage::disk('minio')->put($img, $val['header']['image']);
                        }
                    }

                    $create_header = Header::create([
                        'content_id' => $create_id,
                        'image_banner' => $img_banner,
                        'image' => $img,
                        'title' => @$val['header']['title'],
                        'subtitle' => @$val['header']['subtitle'],
                        'desc' => @$val['header']['desc'],
                        'create_dtm' => Carbon::now(),
                        'update_dtm' => null,
                        'start_dtm' => @$val['start_dtm'],
                        'end_dtm' => @$val['end_dtm'],
                        'url' => @$val['url'],
                    ]);

                    if (!$create_header) {
                        DB::rollback();
    
                        $result->code = 4;
                        $result->info = "Failed save header";
                        $result->data = null;
    
                        return response()->json($result, $result->status);
                    }

                    $header_id = $create_header->id;

                    $body = $val['body'];
                    if ($body) {
                        foreach ($body as $keys => $row) {
                            $img_banner_body = '';
                            if (isset($row['image_banner'])) {
                                if(strlen($row['image_banner']) <=3){
                                    $img_banner_body = $row['image_banner'];
                                }else{
                                    $img_banner_body = 'product/cms/body/'.$header_id.'/'.$keys.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                    Storage::disk('minio')->put($img_banner_body, $row['image_banner']);
                                }
                                
                            }
        
                            $img_body = '';
                            if (isset($row['image'])) {
                                if(strlen($row['image']) <=3){
                                    $img_body = $row['image'];
                                }else{
                                    $img_body = 'product/cms/body/'.$header_id.'/'.$keys.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                                    Storage::disk('minio')->put($img_body, $row['image']);
                                }
                                
                            }
                            
                            $create_body = Detail::create([
                                'image_banner' => $img_banner_body,
                                'image' => $img_body,
                                'header_id' => $header_id,
                                'title' => @$row['title'],
                                'subtitle' => @$row['subtitle'],
                                'desc' => @$row['desc'],
                                'url' => @$row['url'],
                                'create_dtm' => Carbon::now(),
                                'update_dtm' => null,
                                'start_date' => @$row['start_date'],
                                'end_date' => @$row['end_date'],
                            ]);
        
                            if (!$create_body) {
                                DB::rollback();
            
                                $result->code = 5;
                                $result->info = "Failed save sub content";
                                $result->data = null;
            
                                return response()->json($result, $result->status);
                            }
                        }
                    }
                }
            }

            if ($create_id) {
                DB::commit();

                $result->code = 0;
                $result->info = __("Success create data").' '.$data['name'];
                $result->data = $create_id;
            } else {
                $result->code = 1;
                $result->info = __("Failed create data");
                $result->data = null;
            }
        
            ApmCollector::stopMeasure('content-create-span');
            return response()->json($result, $result->status);
        }  catch (\Throwable $ex) {
            error_log('Error at ' . $ex->getFile() . ' line ' . $ex->getLine() . ': ' . $ex->getMessage()); 

            DB::rollback();
            $result->code = 6;
            $result->info = 'Failed save data';
            $result->data = $ex->getMessage();
            $result->status = 500;

            ApmCollector::stopMeasure('content-create-span');
            return response()->json($result, $result->status);
        }
    }

    function update(Request $request) {
        ApmCollector::startMeasure('content-update-span', 'login', 'measure', 'content update');
        $result = new Result();
        $request->validate([
            'data.content_id' => 'required',
            'data.name' => 'required',
            'data.category_id' => 'required',
            'data.language' => 'required',
            'data.module' => 'required',
            'data.format' => 'required',
            'data.start_date' => 'required',
            'data.content_body' => 'required',
        ]);

        $data = $request->data;
        $user = $request->user;

        // if (array_key_exists('grants', $request->user)) {
        //     if (!in_array(Content::LOADBY_GRANTS_CREATE, $request->user['grants'])) {
        //         $result->code = 3;
        //         $result->info = __("Unauthorized");
        //         return response()->json($result, $result->status);
        //     }
        // }

        $content = Content::find($data['content_id']);

        if (!$content) {
            $result->code = 2;
            $result->info = __("Data not found");
            return response()->json($result, $result->status);
        }

        try {
            DB::beginTransaction();
            $create = Content::where('id', $data['content_id'])->update([
                'name' => isset($data['name']) ? $data['name'] : $content['name'],
                'start_date' => isset($data['start_date']) ? $data['start_date'] : $content['start_date'],
                'end_date' => isset($data['end_date']) ? $data['end_date'] : $content['end_date'],
                'status' => isset($data['status']) ? $data['status'] : $content['status'],
                'language' => isset($data['language']) ? $data['language'] : $content['language'],
                'module' => isset($data['module']) ? $data['module'] : $content['module'],
                'summary' => "",
                'format' => isset($data['format']) ? $data['format'] : $content['format'],
                'update_dtm' => Carbon::now(),
                'create_by' => $user['sub'],
                'category_id' => isset($data['category_id']) ? $data['category_id'] : $content['category_id'],
            ]);

            // end date header
            $dbHeader = Header::where('content_id', $data['content_id']);
            $endHeader = $dbHeader->update(['end_dtm' => Carbon::now(), 'update_dtm' => Carbon::now()]);
            $HeaderId = $dbHeader->get()->toArray();

            // end date detail
            $arrHeaderId = [];
            if (isset($HeaderId)) {
                foreach ($HeaderId as $key) {
                    $arrHeaderId[] = $key['id'];
                }
            }

            $endDetail = Detail::whereIn('header_id', $arrHeaderId)->update(['end_date' => Carbon::now(), 'update_dtm' => Carbon::now()]);

            if ($data['content_body']) {
                $value = $data['content_body']['value'];
                foreach ($value as $key => $val) {
                    if (isset($val['id'])) {
                        $dataHeader = Header::where('id', $val['id'])->first();
                        $img_banner = '';
                        if (isset($val['image_banner'])) {
                            if(strlen($val['image_banner']) <=3){
                                $img_banner = $val['image_banner'];
                            }else{
                                $img_banner = $val['image_banner'];
                                if (!filter_var($val['image_banner'], FILTER_VALIDATE_URL)) {
                                    $img_banner = isset($dataHeader['image_banner']) && !empty($dataHeader['image_banner']) ? str_replace(env('RETAIL_BASEPATH').'/api/retail/get-image?path=', '', $dataHeader['image_banner']) : 'product/cms/header/'.$data['content_id'].'/'.$key.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                    Storage::disk('minio')->put($img_banner, $val['image_banner']);
                                }
                            }
                        }
    
                        $img = '';
                        if (isset($val['header']['image'])) {
                            if(strlen($val['header']['image']) <=3){
                                $img = $val['header']['image'];
                            }else{
                                $img = $val['header']['image'];
                                if (!filter_var($val['header']['image'], FILTER_VALIDATE_URL)) {
                                    $img = isset($dataHeader['image']) && !empty($dataHeader['image']) ? str_replace(env('RETAIL_BASEPATH').'/api/retail/get-image?path=', '', $dataHeader['image']) : 'product/cms/header/'.$data['content_id'].'/'.$key.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                                    Storage::disk('minio')->put($img, $val['header']['image']);
                                }
                            }
                        }
    
                        $create_header = Header::create([
                            'content_id' => $data['content_id'],
                            'image_banner' => $img_banner,
                            'image' => $img,
                            'title' => isset($val['header']['title']) ? $val['header']['title'] : (isset($dataHeader['title']) ? $dataHeader['title'] : null),
                            'subtitle' => isset($val['header']['subtitle']) ? $val['header']['subtitle'] : (isset($dataHeader['subtitle']) ? $dataHeader['subtitle'] : null),
                            'desc' => isset($val['header']['desc']) ? $val['header']['desc'] : (isset($dataHeader['desc']) ? $dataHeader['desc'] : null),
                            'create_dtm' => Carbon::now(),
                            'update_dtm' => null,
                            'start_dtm' => isset($val['start_dtm']) ? $val['start_dtm'] : (isset($dataHeader['start_dtm']) ? $dataHeader['start_dtm'] : null),
                            'end_dtm' => $val['end_dtm'],
                            'url' => isset($val['url']) ? $val['url'] : (isset($dataHeader['url']) ? $dataHeader['url'] : null),
                        ]);
    
                        if (!$create_header) {
                            DB::rollback();
        
                            $result->code = 4;
                            $result->info = "Failed update header";
                            $result->data = null;
        
                            return response()->json($result, $result->status);
                        }

                        $header_id = $create_header->id;

                        $body = $val['body'];
                        if ($body) {
                            foreach ($body as $keys => $row) {
                                $dataDetail = Detail::where('id', $row['detail_id'])->first();

                                $img_banner_body = '';
                                if (isset($row['image_banner'])) {
                                    if(strlen($row['image_banner']) <=3){
                                        $img_banner_body = $row['image_banner'];
                                    }else{
                                        $img_banner_body = $row['image_banner'];
                                        if (!filter_var($row['image_banner'], FILTER_VALIDATE_URL)) {
                                            $img_banner_body = isset($dataDetail['image_banner']) && !empty($dataDetail['image_banner']) ? str_replace(env('RETAIL_BASEPATH').'/api/retail/get-image?path=', '', $dataDetail['image_banner']) : 'product/cms/body/'.$val['id'].'/'.$keys.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                            Storage::disk('minio')->put($img_banner_body, $row['image_banner']);
                                        }
                                    }
                                }
            
                                $img_body = '';
                                if (isset($row['image'])) {
                                    if(strlen($row['image']) <=3){
                                        $img_body = $row['image'];
                                    }else{
                                        $img_body = $row['image'];
                                        if (!filter_var($row['image_banner'], FILTER_VALIDATE_URL)) {
                                            $img_body = isset($dataDetail['image']) && !empty($dataDetail['image']) ? str_replace(env('RETAIL_BASEPATH').'/api/retail/get-image?path=', '', $dataDetail['image']) : 'product/cms/body/'.$val['id'].'/'.$keys.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                                            Storage::disk('minio')->put($img_body, $row['image']);
                                        }
                                    }
                                }
            
                                $create_body = Detail::create([
                                    'header_id' => $header_id,
                                    'image_banner' => $img_banner_body,
                                    'image' => $img_body,
                                    'title' => isset($row['title']) ? $row['title'] : (isset($dataDetail['title']) ? $dataDetail['title'] : null),
                                    'subtitle' => isset($row['subtitle']) ? $row['subtitle'] : (isset($dataDetail['subtitle']) ? $dataDetail['subtitle'] : null),
                                    'desc' => isset($row['desc']) ? $row['desc'] : (isset($dataDetail['desc']) ? $dataDetail['desc'] : null),
                                    'url' => isset($row['url']) ? $row['url'] : (isset($dataDetail['url']) ? $dataDetail['url'] : null),
                                    'create_dtm' => Carbon::now(),
                                    'update_dtm' => null,
                                    'start_date' => isset($row['start_date']) ? $row['start_date'] : (isset($dataDetail['start_date']) ? $dataDetail['start_date'] : null),
                                    'end_date' => $row['end_date'],
                                ]);
            
                                if (!$create_body) {
                                    DB::rollback();
                
                                    $result->code = 5;
                                    $result->info = "Failed update sub content";
                                    $result->data = null;
                
                                    return response()->json($result, $result->status);
                                }
                            }
                        }
                    } else {
                        $img_banner = '';
                        if (isset($val['image_banner'])) {
                            if(strlen($val['image_banner']) <=3){
                                $img_banner = $val['image_banner'];
                            }else{
                                $img_banner = 'product/cms/header/'.$data['content_id'].'/'.$key.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_banner, $val['image_banner']);
                            }
                        }

                        $img = '';
                        if (isset($val['header']['image'])) {
                            if(strlen($val['header']['image']) <=3){
                                $img = $val['header']['image'];
                            }else{
                                $img = 'product/cms/header/'.$data['content_id'].'/'.$key.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img, $val['header']['image']);
                            }
                        }
                        
                        $create_header = Header::create([
                            'content_id' => $data['content_id'],
                            'image_banner' => $img_banner,
                            'image' => $img,
                            'title' => @$val['header']['title'],
                            'subtitle' => @$val['header']['subtitle'],
                            'desc' => @$val['header']['desc'],
                            'create_dtm' => Carbon::now(),
                            'update_dtm' => null,
                            'start_dtm' => @$val['start_dtm'],
                            'end_dtm' => @$val['end_dtm'],
                            'url' => @$val['url'],
                        ]);

                        if (!$create_header) {
                            DB::rollback();
        
                            $result->code = 4;
                            $result->info = "Failed save header";
                            $result->data = null;
        
                            return response()->json($result, $result->status);
                        }

                        $header_id = $create_header->id;

                        $body = $val['body'];
                        if ($body) {
                            foreach ($body as $keys => $row) {
                                $img_banner_body = '';
                                if (isset($row['image_banner'])) {
                                    if(strlen($row['image_banner']) <=3){
                                        $img_banner_body = $row['image_banner'];
                                    }else{
                                        $img_banner_body = 'product/cms/body/'.$header_id.'/'.$keys.'/'.'image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                        Storage::disk('minio')->put($img_banner_body, $row['image_banner']);
                                    }
                                }
            
                                $img_body = '';
                                if (isset($row['image'])) {
                                    if(strlen($row['image']) <=3){
                                        $img_body = $row['image'];
                                    }else{
                                        $img_body = 'product/cms/body/'.$header_id.'/'.$keys.'/'.'image/'.Carbon::now()->format('YmdHis').'.jpg';
                                        Storage::disk('minio')->put($img_body, $row['image']);
                                    }
                                }
            
                                $create_body = Detail::create([
                                    'image_banner' => $img_banner_body,
                                    'image' => $img_body,
                                    'header_id' => $header_id,
                                    'title' => @$row['title'],
                                    'subtitle' => @$row['subtitle'],
                                    'desc' => @$row['desc'],
                                    'url' => @$row['url'],
                                    'create_dtm' => Carbon::now(),
                                    'update_dtm' => null,
                                    'start_date' => @$row['start_date'],
                                    'end_date' => @$row['end_date'],
                                ]);
            
                                if (!$create_body) {
                                    DB::rollback();
                
                                    $result->code = 5;
                                    $result->info = "Failed save sub content";
                                    $result->data = null;
                
                                    return response()->json($result, $result->status);
                                }
                            }
                        }
                    }
                }
            }

            if ($create) {
                DB::commit();

                $result->code = 0;
                $result->info = __("Success update data").' '.$data['name'];
                $result->data = $data['content_id'];
            } else {
                $result->code = 1;
                $result->info = __("Failed update data");
                $result->data = null;
            }
        
            ApmCollector::stopMeasure('content-update-span');
            return response()->json($result, $result->status);
        }  catch (\Throwable $ex) {
            error_log('Error at ' . $ex->getFile() . ' line ' . $ex->getLine() . ': ' . $ex->getMessage()); 

            DB::rollback();
            $result->code = 6;
            $result->info = 'Failed update data';
            $result->data = $ex->getMessage();
            $result->status = 500;

            ApmCollector::stopMeasure('content-update-span');
            return response()->json($result, $result->status);
        }
    }

    function delete(Request $request) {
        ApmCollector::startMeasure('content-delete-span', 'login', 'measure', 'content Delete');
        $request->validate([
            'data.id' => 'required',
        ]);

        $result = new Result();
        $post = $request->data;
        $user = $request->user;

        // if ($request->user['grants']) {
        //     if (!in_array(Station::LOADBY_GRANTS_DELETE, $request->user['grants'])) {
        //         $result->code = 3;
        //         $result->info = __("Unauthorized");
        //         return response()->json($result, $result->status);
        //     }
        // }

        $content = Content::where('id', $post['id'])->first();

        if (!$content) {
            $result->code = 1;
            $result->info = __("Data not found");
            return response()->json($result, $result->status);
        }
        
        try {
            DB::beginTransaction();

            $header = Header::where('content_id', $post['id'])->get()->toArray();

            $array_header_id = [];
            if ($header) {
                foreach ($header as $head) {
                    array_push($array_header_id, $head['id']);
                }
            }

            $delete_body = Detail::whereIn('id', $array_header_id)->delete();
            $delete_header = Header::where('content_id', $post['id'])->delete();
            if (!$delete_header) {
                DB::rollback();
            
                $result->code = 3;
                $result->info = "Failed delete header";
                $result->data = null;

                return response()->json($result, $result->status);
            }

            $delete_content = Content::where('id', $post['id'])->delete();

            if (!$delete_content) {
                DB::rollback();
            
                $result->code = 3;
                $result->info = "Failed delete content";
                $result->data = null;

                return response()->json($result, $result->status);
            }
            
            DB::commit();
            $result->code = 0;
            $result->info = __("Success delete data").' '.$post['id'];
            $result->data = $delete_content;
            
            ApmCollector::stopMeasure('content-delete-span');
            return response()->json($result,$result->status);
        } catch(\Exception $ex) {
            error_log('Error at ' . $ex->getFile() . ' line ' . $ex->getLine() . ': ' . $ex->getMessage()); 

            DB::rollback();
            $result->code = 1111;
            $result->info = "Failed delete data";
            $result->data = null;
            ApmCollector::stopMeasure('content-delete-span');
            return response()->json($result,$result->status);
        }
    }

    public function getContents(Request $request) {
        ApmCollector::startMeasure('content-get-span', 'login', 'measure', 'content get');
        try {
            $imageBasepath = env('IMAGE_BASEPATH_URL','https://dev-retail-pgnmobile.pgn.co.id/api/retail/get-image?path=');
            $result = new Result();
            $post = $request->query();
    
            $content = Content::select('content.*', 'content_status.status_name', 'content_category.category_name')
            ->join('cms.content_status', 'content.status', '=', 'content_status.id')
            ->join('cms.content_category', 'content.category_id', '=', 'content_category.id'); 
            
            if (isset($post['id']) && !empty($post['id'])) {
                $content = $content->where('content.id', $post['id'])->orderBy('content.id', 'asc');
            } else {
                $content = $content->where('content.category_id', $post['category_id'])
                ->where('content.language', $post['lang']);
        
                if ($post['mode'] == 'last') $content = $content->limit(1)->orderBy('content.id', 'desc');
            }

            $content = $content->get()->toArray();

            if (!$content) {
                $result->code = 2;
                $result->info = __('Data not found');
                return response()->json($result, $result->status);
            }
    
            foreach ($content as &$item) {
                $header = Header::where('content_id', $item['id'])
                    ->whereNull('end_dtm')->orderBy('id', 'asc')->get()->toArray();
                $dataContent = [];
                if ($header) {
                    foreach ($header as $head) {
                        $dataHeader['id'] = $head['id'];
                        $dataHeader['image_banner'] = strlen($head['image_banner']) < 10 ? $head['image_banner'] : $imageBasepath.$head['image_banner'];
                        $dataHeader['start_dtm'] = $head['start_dtm'];
                        $dataHeader['end_dtm'] = $head['end_dtm'];
                        $dataHeader['url'] = $head['url'];
                        $dataHeader['header'] = [
                            'image' => strlen($head['image']) < 10 ? $head['image'] : $imageBasepath.$head['image'],
                            'title' => $head['title'],
                            'subtitle' => $head['subtitle'],
                            'desc' => $head['desc'],
                        ];
    
                        $details = Detail::where('header_id', $head['id'])
                            ->whereNull('end_date')->orderBy('id', 'asc')->get()->toArray();
                        $dataDetails = [];
                        if ($details) {
                            foreach ($details as $body) {
                                $dataDetails[] = [
                                    'detail_id' => $body['id'],
                                    'image_banner' => strlen($body['image_banner']) < 10 ? $body['image_banner'] : $imageBasepath.$body['image_banner'],
                                    'image' => strlen($body['image']) < 10 ? $body['image'] : $imageBasepath.$body['image'],
                                    'url' => $body['url'],
                                    'title' => $body['title'],
                                    'subtitle' => $body['subtitle'],
                                    'desc' => $body['desc'],
                                    'start_date' => $body['start_date'],
                                    'end_date' => $body['end_date']
                                ];
                            }
                        }
    
                        $dataHeader['body'] = $dataDetails;
                        $dataContent[] = $dataHeader;
                    }
                }
                $item['content_body'] = [
                    'value' => $dataContent,
                    'summary' => '',
                    'format' => 'json'
                ];
            }
    
            $result->data = $content;
    
            ApmCollector::stopMeasure('content-get-span');
            return response()->json($result, $result->status);
        } catch (\Throwable $th) {
            error_log('Error at ' . $th->getFile() . ' line ' . $th->getLine() . ': ' . $th->getMessage());
            $result->code = 3;
            $result->info = __('Failed to get data');

            ApmCollector::stopMeasure('content-get-span');
            return response()->json($result, $result->status);
        }
    }
}