<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class NormalizationWord extends Model
{
    protected $table = 'normalization-words';

    public static function getNormalizationWords()
    {
        $data = DB::select( DB::raw("SELECT * FROM `normalization-words`"));

        return $data;
    }

}
