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
    	$tweets = Tweet::where('negated', 1)->get();

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
                $data = $tweet->tweet;
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
                $update->negated = 1;
                $update->save();

                // negated
                $words2 = array();
                $token = strtok($data, $delim);
                while ($token !== false) 
                {
                    $words2[] = $token;
                    $token = strtok($delim);
                }

                foreach ($words2 as $key => $word) 
                {
                    if($word == 'tidak')
                    {
                        unset($words2[$key]);
                    }
                }

                $words2 = implode(" ", $words2);

                if($tweet->sentiment_id == 3)
                {
                    $sentiment = 3;
                }
                else if($tweet->sentiment_id == 1)
                {
                    $sentiment = 2;
                }
                else
                {
                    $sentiment = 1;
                }

                $update = new Tweet;
                $update->tweet = $words2;
                $update->sentiment_id = $sentiment;
                $update->negated = 1;
                $update->save();
            }
        }

        return Redirect::to('dashboard/negation-handling');
    }
}
