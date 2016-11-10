<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class Stopword extends Model
{
    protected $table = 'stopwords';

    public static function getStopwords()
    {
        $data = DB::select( DB::raw("SELECT * FROM `stopwords`"));

        return $data;
    }
}
