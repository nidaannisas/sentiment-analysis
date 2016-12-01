<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class NormalizationProcess extends Model
{
    protected $table = 'normalization_process';

    public static function get()
    {
        $data = DB::select( DB::raw("SELECT * FROM `normalization_process` ORDER BY id DESC LIMIT 1"));

        if(!empty($data))
            return $data[0];
        else
            return $data;
    }
}
