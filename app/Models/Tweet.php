<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class Tweet extends Model
{
    protected $table = 'tweets';

    public function sentiment()
    {
        return $this->belongsTo(Sentiment::class, 'sentiment_id');
    }

}
