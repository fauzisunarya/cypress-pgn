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

class Header extends Model
{
    use HasApiTokens, HasFactory;
    
    public $table = 'cms.content_header';
    public $timestamps = false;

    public $fillable = [
        'id',
        'content_id',
        'image_banner',
        'image',
        'title',
        'subtitle',
        'desc',
        'create_dtm',
        'update_dtm',
        'start_dtm',
        'end_dtm',
        'url',
    ];
    
}
