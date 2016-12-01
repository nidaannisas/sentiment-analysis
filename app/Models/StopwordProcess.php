<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class StopwordProcess extends Model
{
    protected $table = 'stopword_process';

    public static function get()
    {
        $data = DB::select( DB::raw("SELECT * FROM `stopword_process` ORDER BY id DESC LIMIT 1"));

        if(!empty($data))
            return $data[0];
        else
            return $data;
    }
}
