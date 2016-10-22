<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class PembagianData extends Model
{
    protected $table = 'pembagian-data';

    public static function defaultTestTweet()
    {
        $data = DB::select( DB::raw("UPDATE `tweets` SET `type`='TEST' WHERE 1"));

        return $data;
    }
}
