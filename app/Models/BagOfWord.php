<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

Class BagOfWord extends Model
{
    protected $table = 'bag-of-words';

    public static function search($word)
    {
        $data = DB::select( DB::raw("SELECT * FROM `bag-of-words` WHERE word = :word "),
                    array(
                        "word"    => $word
                    ));
    
        if(!empty($data))
            return $data[0];
        else
            return $data;
    }
}
