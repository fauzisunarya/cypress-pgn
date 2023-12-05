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

class Status extends Model
{
    use HasApiTokens, HasFactory;
    
    public $table = 'cms.content_status';
    public $timestamps = false;

    public $fillable = [
        'id',
        'status_name',
        'description',
    ];
    
}
