<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Models\TweetResult;
use App\Http\Requests;
use Excel;
use Redirect;
use Input;

class ClusteringController extends Controller
{
    public function index()
    {
    	$sentiments = Sentiment::all();
    	$tweets = Tweet::all();

    	return view('clustering.index')
    		->with('sentiments', $sentiments)
    		->with('tweets', $tweets);
    }

}
