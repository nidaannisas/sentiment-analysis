<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Http\Requests;

use Redirect;

class EvaluationController extends Controller
{
    public function index()
    {
    	$tweets = TweetTest::orderBy('id', 'DESC')->get();

    	return view('evaluation.index')
    		->with('tweets', $tweets);
    }
}
