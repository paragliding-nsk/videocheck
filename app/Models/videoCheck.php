<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;

use App\Models\Medcard;

class Video extends Model
{
    use SoftDeletes;

    protected $table = 'videos';
    protected $guarded = [];
    
  
    static public function checkNewVideo($xApiKey){
    $videos = Video::all();

  
