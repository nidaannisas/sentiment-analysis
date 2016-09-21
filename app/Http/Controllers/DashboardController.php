<?php

namespace App\Http\Controllers;
use App\Models\Tweet;

class DashboardController extends Controller
{

    public function index()
    {
    	$tweet = Tweet::count();
    	$positive = Tweet::where('sentiment_id', 1)->count();
    	$negative = Tweet::where('sentiment_id', 2)->count();
    	$neutral = Tweet::where('sentiment_id', 3)->count();
    	
    	return view('dashboard')
    		->with('tweet', $tweet)
    		->with("positive", $positive)
    		->with("negative", $negative)
    		->with("neutral", $neutral);
    }

}
