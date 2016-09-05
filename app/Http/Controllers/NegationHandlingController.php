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
        $tweets = Tweet::all();

        foreach($tweets as $tweet)
        {
            if (strpos($tweet->tweet, 'tidak') !== false) 
            {
                // tokenize
                $words = array();
                $delim = " \n.,;-()";
                $tok = strtok($tweet->tweet, $delim);
                while ($tok !== false) 
                {
                    $words[] = $tok;
                    $tok = strtok($delim);
                }

                foreach ($words as $key => $word) 
                {
                    if($word == 'tidak')
                    {
                        $words[$key] = $word.'_'.$words[$key + 1];
                        unset($words[$key + 1]);
                    }
                }

                $words = implode(" ", $words);

                $update = Tweet::find($tweet->id);
                $update->tweet = $words;
                $update->save();
            }
        }

        return Redirect::to('dashboard/negation-handling');
    }
}
