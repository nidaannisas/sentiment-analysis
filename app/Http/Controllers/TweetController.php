<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Http\Requests;

use Redirect;

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

    public function store(Request $request)
    {
    	$tweet = $request->input('tweet');
    	$sentiment_id = $request->input('sentiment');

    	$tweets = new Tweet;
    	$tweets->tweet = $tweet;
    	$tweets->sentiment_id = $sentiment_id;
    	$tweets->save();

    	return Redirect::to('dashboard/tweets');
    }
}
