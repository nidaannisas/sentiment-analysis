<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Http\Requests;

use Redirect;

class NegationHandlingController extends Controller
{
    public function index()
    {
    	$tweets = Tweet::all();

    	return view('negation-handling.index')
    		->with('tweets', $tweets);
    }

    public function process()
    {

    }
}
