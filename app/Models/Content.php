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
    
}
