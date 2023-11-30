<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Result;
use App\Models\Status;
use Illuminate\Support\Facades\Storage;
use AG\ElasticApmLaravel\Facades\ApmCollector;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller {
    public function index(Request $request) {
        $result = new Result();
        
        // if (array_key_exists('grants', $request->user)) {
        //     if (!in_array(Status::LOADBY_GRANTS_LIST, $request->user['grants'])) {
        //         $result->code = 3;
        //         $result->info = __("Unauthorized");
        //         return response()->json($result, $result->status);
        //     }
        // }

        $model = new Category();
        DB::enableQueryLog();

        $data = $request->data;

        $data['search'] = str_replace("'", "''", $data['search']);
        $col = 'id';
        
        if(isset($data['order']['column']) && !empty($data['order']['column'])){
            $col = $data['order']['column'];
        }

        $length = empty($data['length'])?10:$data['length'];
        if($data['search']){
            $model->where(function ($query) use ($data) {
                $query->where('category_name', 'ilike', '%' . $data['search'] . '%')
                    ->orWhere('category_description', 'ilike', '%' . $data['search'] . '%');
            });
        }

        $log = $model->orderBy($col, $data['order']['dir'])->paginate($length);
        $offset = ($log->currentPage()* $log->perPage()) - $log->perPage();
        for($i = 0; $i < $log->count();$i++){
            $log[$i]->no = $i+$offset+1;
        }

        $result->code = 0;
        $result->info = __('Success');
        $result->data = $log;
        $result->status = 200;

        return response()->json($result, $result->status);
    }
}
