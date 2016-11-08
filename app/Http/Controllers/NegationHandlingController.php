<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Http\Requests;
use DB;
use Redirect;

class NegationHandlingController extends Controller
{
    public function index()
    {
    	$tweets = Tweet::where('negated', 1)->get();

    	return view('negation-handling.index')
    		->with('tweets', $tweets);
    }

    public function negationTest()
    {
        $tweets = Tweet::getTest();

        DB::beginTransaction();
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
                    if($word == 'tidak' && $key < count($words)-1)
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

            }
        }
        DB::commit();
    }

    public function negationTrain()
    {
        $tweets = Tweet::getTrain();

        DB::beginTransaction();
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
                    if($word == 'tidak' && $key < count($words)-1)
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

                if($tweet->sentiment_id == 1)
                {
                    $sentiment = 2;
                }
                else if($tweet->sentiment_id == 2)
                {
                    $sentiment = 1;
                }

                if($tweet->sentiment_id != 3)
                {
                    $update = new Tweet;
                    $update->tweet = $words2;
                    $update->sentiment_id = $sentiment;
                    $update->negated = 1;
                    $update->save();
                }
            }
        }
        DB::commit();
    }

    public function process()
    {
        $this->negationTrain();
        $this->negationTest();
        return Redirect::to('dashboard/negation-handling');
    }
}
