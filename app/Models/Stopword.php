<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class Stopword extends Model
{
    protected $table = 'stopwords';

    public static function getStopwords()
    {
        $data = DB::select( DB::raw("SELECT * FROM `stopwords` ORDER BY word ASC"));

        return $data;
    }
}
