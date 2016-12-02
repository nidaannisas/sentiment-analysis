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

    // public static function count($token_id)
    // {
    //     $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm` WHERE token_id = :token_id "),
    //                 array(
    //                     "token_id"    => $token_id
    //                 ));
    //
    //     if(!empty($data))
    //         return $data[0]->count;
    //     else
    //         return 0;
    // }
    //
    // public static function countPositive($token_id)
    // {
    //     $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 1 AND `tweets`.`id` = `tdm`.`tweet_id`"),
    //                 array(
    //                     "token_id"    => $token_id
    //                 ));
    //
    //     if(!empty($data))
    //         return $data[0]->count;
    //     else
    //         return 0;
    // }
    //
    // public static function countNegative($token_id)
    // {
    //     $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 2 AND `tweets`.`id` = `tdm`.`tweet_id`"),
    //                 array(
    //                     "token_id"    => $token_id
    //                 ));
    //
    //     if(!empty($data))
    //         return $data[0]->count;
    //     else
    //         return 0;
    // }
    //
    // public static function countNeutral($token_id)
    // {
    //     $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tdm`, `tweets` WHERE `tdm`.token_id = :token_id AND `tweets`.`sentiment_id` = 3 AND `tweets`.`id` = `tdm`.`tweet_id`"),
    //                 array(
    //                     "token_id"    => $token_id
    //                 ));
    //
    //     if(!empty($data))
    //         return $data[0]->count;
    //     else
    //         return 0;
    // }

    public static function countPositiveWord($word)
    {
        $data = DB::select( DB::raw("SELECT count_positive FROM `bag-of-words` WHERE `word` = :word"),
                    array(
                        "word"    => $word
                    ));

        if(!empty($data))
            return $data[0]->count_positive;
        else
            return 0;
    }

    public static function countNegativeWord($word)
    {
        $data = DB::select( DB::raw("SELECT count_negative FROM `bag-of-words` WHERE `word` = :word"),
                    array(
                        "word"    => $word
                    ));

        if(!empty($data))
            return $data[0]->count_negative;
        else
            return 0;
    }

    public static function countNeutralWord($word)
    {
        $data = DB::select( DB::raw("SELECT count_neutral FROM `bag-of-words` WHERE `word` = :word"),
                    array(
                        "word"    => $word
                    ));

        if(!empty($data))
            return $data[0]->count_neutral;
        else
            return 0;
    }

    public static function countWord($word)
    {
        return BagOfWord::countPositiveWord($word) + BagOfWord::countNegativeWord($word) + BagOfWord::countNeutralWord($word);
    }

    public static function countWordPositive()
    {
        $data = DB::select( DB::raw("SELECT SUM(count_positive) as total FROM `bag-of-words`"));

        if(!empty($data))
            return $data[0]->total;
        else
            return 0;
    }

    public static function countWordNegative()
    {
        $data = DB::select( DB::raw("SELECT SUM(count_negative) as total FROM `bag-of-words`"));

        if(!empty($data))
            return $data[0]->total;
        else
            return 0;
    }

    public static function countWordNeutral()
    {
        $data = DB::select( DB::raw("SELECT SUM(count_neutral) as total FROM `bag-of-words`"));

        if(!empty($data))
            return $data[0]->total;
        else
            return 0;
    }
}
