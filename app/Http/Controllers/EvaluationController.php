<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\NormalizationWord;
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

    public function quicksort($seq)
    {
        if(!count($seq)) return $seq;

        $k = $seq[0];
        $x = $y = array();

        for($i=count($seq); --$i;)
        {
            if($seq[$i] <= $k)
            {
                $x[] = $seq[$i];
            }
            else
            {
                $y[] = $seq[$i];
            }
        }

        return array_merge($this->quicksort($x), array($k), $this->quicksort($y));
    }

    public function evaluate()
    {
        $start = microtime(true);

        $tweets = Tweet::all();

        $words = array();
        $count_positive = 0;
        $count_negative = 0;
        $count_neutral = 0;

        foreach($tweets as $tweet)
        {
            // remove except letter
            //$tweet->tweet = preg_replace('#^https?://*/', '', $tweet->tweet);
            $tweet->tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/', '#^https?([a-zA-Z_ -]*)#'), array('', ' ', ''), $tweet->tweet);

            // to lower
            $tweet->tweet = strtolower($tweet->tweet);

            $delim = " \n.,;-()";
            $tok = strtok($tweet->tweet, $delim);
            while ($tok !== false)
            {
                $words[] = $tok;
                $tok = strtok($delim);

                if($tweet->sentiment_id == 1)
                {
                    $count_positive = $count_positive + 1;
                }
                else if($tweet->sentiment_id == 2)
                {
                    $count_negative = $count_negative + 1;
                }
                else
                {
                    $count_neutral = $count_neutral + 1;
                }
            }
        }

        $yaya = $this->quicksort($words);

        $time_elapsed_secs = microtime(true) - $start;

        echo $time_elapsed_secs;
    }
}
