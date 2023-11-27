<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Result;
use App\Models\Content;
use App\Models\Content\Header;
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
}
