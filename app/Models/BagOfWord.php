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

    public static function count($token_id)
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm` WHERE token_id = :token_id "),
                    array(
                        "token_id"    => $token_id
                    ));
    
        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

    public static function countPositive($token_id)
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 1 AND `tweets`.`id` = `tdm`.`tweet_id`"),
                    array(
                        "token_id"    => $token_id
                    ));
    
        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

    public static function countNegative($token_id)
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 2 AND `tweets`.`id` = `tdm`.`tweet_id`"),
                    array(
                        "token_id"    => $token_id
                    ));
    
        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

    public static function countNeutral($token_id)
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 3 AND `tweets`.`id` = `tdm`.`tweet_id`"),
                    array(
                        "token_id"    => $token_id
                    ));
    
        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }
}
