<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class TweetTest extends Model
{
    protected $table = 'tweets_tests';

    public function sentiment()
    {
        return $this->belongsTo(Sentiment::class, 'sentiment_id');
    }

}
