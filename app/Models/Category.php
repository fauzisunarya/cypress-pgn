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

class Category extends Model
{
    use HasApiTokens, HasFactory;
    
    public $table = 'cms.content_category';
    public $timestamps = false;

    public $fillable = [
        'id',
        'category_name',
        'category_description',
    ];
    
}
