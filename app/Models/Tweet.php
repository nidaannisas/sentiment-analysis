<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class Tweet extends Model
{
    protected $table = 'tweets';

    public function sentiment()
    {
        return $this->belongsTo(Sentiment::class, 'sentiment_id');
    }

    public static function countPositive()
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tweets` WHERE sentiment_id = 1"));

        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

    public static function getTweets()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets`"));

        return $data;
    }

    public static function getTrain()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets` WHERE type = 'TRAIN'"));

        return $data;
    }

    public static function getTest()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets` WHERE type = 'TEST'"));

        return $data;
    }

    public static function getPositive()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets` WHERE sentiment_id = 1"));

        return $data;
    }

    public static function getNegative()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets` WHERE sentiment_id = 2"));

        return $data;
    }

    public static function getNeutral()
    {
        $data = DB::select( DB::raw("SELECT * FROM `tweets` WHERE sentiment_id = 3"));

        return $data;
    }

    public static function countNegative()
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tweets` WHERE sentiment_id = 2"));

        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

    public static function countNeutral()
    {
        $data = DB::select( DB::raw("SELECT COUNT(*) as count FROM `tweets` WHERE sentiment_id = 3"));

        if(!empty($data))
            return $data[0]->count;
        else
            return $data;
    }

}
