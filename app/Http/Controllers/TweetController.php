<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Http\Requests;

class TweetController extends Controller
{
    public function index()
    {
    	$sentiments = Sentiment::all();

    	return view('tweets.index')
    		->with('sentiments', $sentiments);
    }
}
