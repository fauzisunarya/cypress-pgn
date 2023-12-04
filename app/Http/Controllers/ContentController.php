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
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'],
                'language' => $data['language'],
                'module' => $data['module'],
                'summary' => "",
                'format' => $data['format'],
                'create_dtm' => Carbon::now(),
                'update_dtm' => null,
                'create_by' => $user['sub'],
                'category_id' => $data['category_id'],
            ]);

            $create_id = $create->id;

            if ($data['content_body']) {
                $value = $data['content_body']['value'];
                foreach ($value as $val) {
                    $img_banner = '';
                    if (isset($val['img_banner'])) {
                        $img_banner = env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/header/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                        Storage::disk('minio')->put($img_banner, $val['img_banner']);
                    }

                    $img = '';
                    if (isset($val['header']['image'])) {
                        $img = env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/header/image/'.Carbon::now()->format('YmdHis').'.jpg';
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
                            if (isset($row['img_banner'])) {
                                $img_banner_body = env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/body/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_banner_body, $row['img_banner']);
                            }
        
                            $img_body = '';
                            if (isset($row['image'])) {
                                $img_body = env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/body/image/'.Carbon::now()->format('YmdHis').'.jpg';
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
            echo "<pre>"; print_r('Error at ' . $ex->getFile() . ' line ' . $ex->getLine() . ': ' . $ex->getMessage()); echo "</pre>"; die('');
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
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'],
                'language' => $data['language'],
                'module' => $data['module'],
                'summary' => "",
                'format' => $data['format'],
                'update_dtm' => Carbon::now(),
                'create_by' => $user['sub'],
                'category_id' => $data['category_id'],
            ]);

            if ($data['content_body']) {
                $value = $data['content_body']['value'];
                foreach ($value as $val) {
                    $getImgHeader = Header::where('id', $val['id'])->first();
                    $img_banner = '';
                    if (isset($val['img_banner'])) {
                        $img_banner = $val['img_banner'];
                        if (!filter_var($val['img_banner'], FILTER_VALIDATE_URL)) {
                            $img_banner = isset($getImgHeader['image_banner']) && !empty($getImgHeader['image_banner']) ? $getImgHeader['image_banner'] : env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/header/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                            Storage::disk('minio')->put($img_banner, $val['img_banner']);
                        }
                    }

                    $img = '';
                    if (isset($val['header']['image'])) {
                        $img = $val['header']['image'];
                        if (!filter_var($val['header']['image'], FILTER_VALIDATE_URL)) {
                            $img = isset($getImgHeader['image']) && !empty($getImgHeader['image']) ? $getImgHeader['image'] : env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/header/image/'.Carbon::now()->format('YmdHis').'.jpg';
                            Storage::disk('minio')->put($img, $val['header']['image']);
                        }
                    }

                    $create_header = Header::where('id', $val['id'])->update([
                        'content_id' => $data['content_id'],
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
                        $result->info = "Failed update header";
                        $result->data = null;
    
                        return response()->json($result, $result->status);
                    }

                    $body = $val['body'];
                    if ($body) {
                        foreach ($body as $row) {
                            $getImgBody = Detail::where('id', $row['detail_id'])->first();

                            $img_banner_body = '';
                            if ($row['image_banner'] != null) {
                                $img_banner_body = isset($getImgBody['image_banner']) && !empty($getImgBody['image_banner']) ? $getImgBody['image_banner'] : env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/body/image-banner/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_banner_body, $row['image_banner']);
                            }
        
                            $img_body = '';
                            if ($row['image'] != null) {
                                $img_body = isset($getImgBody['image']) && !empty($getImgBody['image']) ? $getImgBody['image'] : env('RETAIL_BASEPATH').'/api/retail/get-image?path=product/cms/body/image/'.Carbon::now()->format('YmdHis').'.jpg';
                                Storage::disk('minio')->put($img_body, $row['image']);
                            }
        
                            $create_body = Detail::where('id', $row['detail_id'])->update([
                                'image_banner' => $img_banner_body,
                                'image' => $img_body,
                                'header_id' => $val['id'],
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
                                $result->info = "Failed update sub content";
                                $result->data = null;
            
                                return response()->json($result, $result->status);
                            }
                        }
                    }
                }
            }

            if ($create) {
                DB::commit();

                $result->code = 0;
                $result->info = __("Success update data");
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
            $result->info = __("Success delete data");
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
            $result = new Result();
            $post = $request->query();
            $result->data = $post;
    
            $content = Content::select('content.*', 'content_status.status_name')
            ->join('cms.content_status', 'content.status', '=', 'content_status.id'); 
            
            if (isset($post['id']) && !empty($post['id'])) {
                $content = $content->where('content.id', $post['id']);
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
                $header = Header::where('content_id', $item['id'])->get()->toArray();
                $dataContent = [];
                if ($header) {
                    foreach ($header as $head) {
                        $dataHeader['id'] = $head['id'];
                        $dataHeader['image_banner'] = $head['image_banner'];
                        $dataHeader['start_dtm'] = $head['start_dtm'];
                        $dataHeader['end_dtm'] = $head['end_dtm'];
                        $dataHeader['url'] = $head['url'];
                        $dataHeader['header'] = [
                            'image' => $head['image'],
                            'title' => $head['title'],
                            'subtitle' => $head['subtitle'],
                            'desc' => $head['desc'],
                        ];
    
                        $details = Detail::where('header_id', $head['id'])->get()->toArray();
                        $dataDetails = [];
                        if ($details) {
                            foreach ($details as $body) {
                                $dataDetails[] = [
                                    'detail_id' => $body['id'],
                                    'image_banner' => $body['image_banner'],
                                    'image' => $body['image'],
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
