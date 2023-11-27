<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Result;
use App\Models\Content;
use App\Models\Content\Header;
use App\Models\Content\Detail;
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

        $data = new Content();

        if (isset($search) && !empty($search)) {
            $data = $data->where('name', 'ILIKE', '%'.$search.'%');
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
            'data.module' => 'required',
            'data.format' => 'required',
            'data.start_date' => 'required',
            'data.end_date' => 'required',
            'data.content_body' => 'required',
            'data.content_body.value' => 'required',
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
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'],
                'language' => $data['language'],
                'module' => $data['module'],
                'summary' => $data['summary'],
                'format' => $data['format'],
                'create_dtm' => Carbon::now(),
                'update_dtm' => Carbon::now(),
                'create_by' => $user['sub'],
                'category_id' => $data['category_id'],
            ]);

            $create_id = $create->id;

            if ($data['content_body']) {
                $value = $data['content_body']['value'];
                foreach ($value as $val) {
                    $img_banner = '';
                    if ($val['image_banner'] != null) {
                        $img_banner = 'product/cms/header/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                        Storage::disk('minio')->put($img_banner, $val['image_banner']);
                    }

                    $img = '';
                    if ($val['header']['image'] != null) {
                        $img = 'product/cms/header/image/'.Carbon::now()->format('YmdHis').'.jpg';
                        Storage::disk('minio')->put($img, $val['header']['image']);
                    }

                    $create_header = Header::create([
                        'content_id' => $create_id,
                        'image_banner' => $img_banner,
                        'image' => $img,
                        'title' => $val['header']['title'],
                        'subtitle' => $val['header']['title'],
                        'desc' => $val['header']['desc'],
                        'create_dtm' => Carbon::now(),
                        'update_dtm' => null,
                        'start_dtm' => $val['start_dtm'],
                        'end_dtm' => $val['end_dtm'],
                        'url' => $val['url'],
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
                        foreach ($body as $row) {

                            $img_banner_body = '';
                            if ($row['image_banner'] != null) {
                                $img_banner = 'product/cms/body/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_banner_body, $row['image_banner']);
                            }
        
                            $img_body = '';
                            if ($row['image'] != null) {
                                $img = 'product/cms/body/image/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_body, $row['image']);
                            }
        
                            $create_body = Detail::create([
                                'image_banner' => $img_banner_body,
                                'image' => $img_body,
                                'header_id' => $header_id,
                                'title' => $row['title'],
                                'subtitle' => $row['title'],
                                'desc' => $row['desc'],
                                'url' => $row['url'],
                                'create_dtm' => Carbon::now(),
                                'update_dtm' => null,
                                'start_date' => $row['start_date'],
                                'end_date' => $row['end_date'],
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
                $result->info = __("Success create data");
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
}
