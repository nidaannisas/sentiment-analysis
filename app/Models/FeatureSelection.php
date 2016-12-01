<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class FeatureSelection extends Model
{
    protected $table = 'feature_selection';

    public static function get()
    {
        $data = DB::select( DB::raw("SELECT * FROM `feature_selection` ORDER BY id DESC LIMIT 1"));

        if(!empty($data))
            return $data[0];
        else
            return $data;
    }
}
