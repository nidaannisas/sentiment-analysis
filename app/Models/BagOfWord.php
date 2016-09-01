<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

Class BagOfWord extends Model
{
    protected $table = 'bag-of-words';

    public static function search($word)
    {
        $data   = DB::table('bag-of-words')
                ->where('word', $word)
                ->get();
    
        if(!empty($data))
            return $data[0];
        else
            return $data;
    }
}
