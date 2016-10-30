<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class NormalizationWord extends Model
{
    protected $table = 'normalization-words';

    public static function getNormalizationWords()
    {
        $data = DB::select( DB::raw("SELECT * FROM `normalization-words` ORDER BY word ASC"));

        return $data;
    }

    public static function search($word)
    {
        $data = DB::select( DB::raw("SELECT * FROM `normalization-words` WHERE word = :word "),
                    array(
                        "word"    => $word
                    ));

        if(!empty($data))
            return $data[0];
        else
            return $data;
    }

}
