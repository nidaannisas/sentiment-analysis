<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class TweetResult extends Model
{
    protected $table = 'tweets_result';

    public function sentiment()
    {
        return $this->belongsTo(Sentiment::class, 'sentiment_id');
    }
}
