<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Http\Requests;

class TweetController extends Controller
{
    public function index()
    {
    	$sentiments = Sentiment::all();
    	$tweets = Tweet::all();

    	return view('tweets.index')
    		->with('sentiments', $sentiments)
    		->with('tweets', $tweets);
    }
}
