<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TweetTest;
use App\Http\Requests;

use Redirect;

class NaiveBayesController extends Controller
{
    public function index()
    {
    	$tweets = TweetTest::all();

    	return view('naive-bayes.index')
    		->with('tweets', $tweets);
    }

    public function classify(Request $request)
    {
    	$tweet = $request->input('tweet');

    	$tweets = new TweetTest;
    	$tweets->tweet = $tweet;
    	$tweets->save();

    	return Redirect::to('dashboard/naive-bayes');
    }

    public function naiveBayes()
    {

    }
}
