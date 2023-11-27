<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Faker\Factory as Faker;
use App\Helper\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Detail extends Model
{
    use HasApiTokens, HasFactory;
    
    public $table = 'cms.content_details';
    public $timestamps = false;

    public $fillable = [
        'id',
        'image_banner',
        'header_id',
        'image',
        'title',
        'subtitle',
        'desc',
        'url',
        'create_dtm',
        'update_dtm',
        'start_date',
        'end_date',
    ];
    
}
