<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetResult;
use App\Models\NegationHandlingProcess;
use App\Http\Requests;
use DB;
use Redirect;

class NegationHandlingController extends Controller
{
    public function index()
    {
    	$tweets = TweetResult::where('negated', 1)->get();
        $process = NegationHandlingProcess::get();

    	return view('negation-handling.index')
            ->with('process', $process)
    		->with('tweets', $tweets);
    }

    public function negation($data)
    {
        if($data == 'TRAIN')
            $tweets = TweetResult::getTrain();
        else
            $tweets = TweetResult::getTest();

        $tweet_negated = 0;
        $count_negated_positive = 0;
        $count_negated_negative = 0;
        $count_negated_neutral = 0;
        $count_negated_positive_result = 0;
        $count_negated_negative_result = 0;

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

                $negated = false;

                foreach ($words as $key => $word)
                {
                    if($word == 'tidak' && $key < count($words)-1)
                    {
                        $negated = true;
                        $words[$key] = $word.'_'.$words[$key + 1];
                        unset($words[$key + 1]);
                    }
                }

                $words = implode(" ", $words);

                if($negated)
                {
                    $tweet_negated++;

                    $update = TweetResult::find($tweet->id);
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
                        if($word == 'tidak' && $key < count($words2)-1)
                        {
                            unset($words2[$key]);
                        }
                    }

                    $words2 = implode(" ", $words2);

                    if($tweet->sentiment_id == 1)
                    {
                        $count_negated_positive++;          // jumlah negated positif
                        $count_negated_negative_result++;   // hasil negated yg negatif

                        $sentiment = 2;
                    }
                    else if($tweet->sentiment_id == 2)
                    {
                        $count_negated_negative++;
                        $count_negated_positive_result++;

                        $sentiment = 1;
                    }
                    else
                    {
                        $count_negated_neutral++;
                    }

                    if($tweet->sentiment_id != 3)
                    {
                        $update = new TweetResult;
                        $update->tweet = $words2;
                        $update->sentiment_id = $sentiment;
                        $update->negated = 1;
                        $update->type = $tweet->type;
                        $update->save();
                    }
                }
            }
        }
        DB::commit();

        $data = (object) array('tweet_negated' => $tweet_negated,
                                'count_negated_positive' => $count_negated_positive,
                                'count_negated_negative' => $count_negated_negative,
                                'count_negated_neutral' => $count_negated_neutral,
                                'count_negated_positive_result' => $count_negated_positive_result,
                                'count_negated_negative_result' => $count_negated_negative_result
                            );

        return $data;
    }

    public function process()
    {
        $start = microtime(true);

        $train = $this->negation('TRAIN');
        $test = $this->negation('TEST');

        $time_elapsed_secs = microtime(true) - $start;

        $negation = new NegationHandlingProcess;
        $negation->tweet_negated_train = $train->tweet_negated;
        $negation->tweet_negated_test = $test->tweet_negated;
        $negation->count_negated_positive = $train->count_negated_positive;
        $negation->count_negated_negative = $train->count_negated_negative;
        $negation->count_negated_neutral = $train->count_negated_neutral;
        $negation->count_negated_positive_result = $train->count_negated_positive_result;
        $negation->count_negated_negative_result = $train->count_negated_negative_result;
        $negation->count_negated_positive_test = $test->count_negated_positive;
        $negation->count_negated_negative_test = $test->count_negated_negative;
        $negation->count_negated_neutral_test = $test->count_negated_neutral;
        $negation->count_negated_positive_result_test = $test->count_negated_positive_result;
        $negation->count_negated_negative_result_test = $test->count_negated_negative_result;
        $negation->process_time = $time_elapsed_secs;
        $negation->save();

        return Redirect::to('dashboard/negation-handling');
    }
}
