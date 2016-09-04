<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class IDF extends Model
{
    protected $table = 'idf';

    public static function calculateIDFWord($word)
    {
        $data = DB::select( DB::raw("SELECT idf FROM `bag-of-words` WHERE `word` = :word"),
                    array(
                        "word"    => $word
                    ));
    
        if(!empty($data))
            return $data[0]->idf;
        else
            return 0;
    }
}
