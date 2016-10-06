<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\NormalizationWord;
use App\Models\Stopword;
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

    public function tokenizing($tweets)
    {
        $count_positive = 0;
        $count_negative = 0;
        $count_neutral = 0;

        $count_tweet_positive = 0;
        $count_tweet_negative = 0;
        $count_tweet_neutral = 0;

        foreach($tweets as $tweet)
        {
            if($tweet->sentiment_id == 1)
                $count_tweet_positive++;
            else if($tweet->sentiment_id == 2)
                $count_tweet_negative++;
            else
                $count_tweet_neutral++;

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

        $words = array_unique($words);
        $words = array_values($words);

        $result = array("words" => $words,
                        "count_positive" => $count_positive,
                        "count_negative" => $count_negative,
                        "count_neutral" => $count_neutral,
                        "count_tweet_positive" => $count_tweet_positive = 0,
                        "count_tweet_negative" => $count_tweet_negative = 0,
                        "count_tweet_neutral" => $count_tweet_neutral = 0,
                    );

        return $result;
    }

    public function normalization($tweets)
    {
        $normalizations = NormalizationWord::all();

        foreach($normalizations as $normalization)
        {
            $search = $this->BinarySearch($tweets, $normalization->word, 0, count($tweets)-1);
            if($search > -1)
            {
                $tweets[$search] = $normalization->normal_word;
            }

        }

        return $tweets;
    }

    public function evaluate()
    {
        $start = microtime(true);

        $tweets = Tweet::getTweets();
        $train = array_slice($tweets,0,7);
        $test = array_slice($tweets,8,10);
        $N = 8;

        // tokenizing
        $tokenizing = $this->tokenizing($train);
        $words = $tokenizing['words'];

        $count_positive = $tokenizing['count_positive'];
        $count_negative = $tokenizing['count_negative'];
        $count_neutral = $tokenizing['count_neutral'];

        $count_tweet_positive = $tokenizing['count_tweet_positive'];
        $count_tweet_negative = $tokenizing['count_tweet_negative'];
        $count_tweet_neutral = $tokenizing['count_tweet_neutral'];

        // sort bag of words
        $words = $this->quicksort($words);

        // words normalization
        $words = $this->normalization($words);

        //
        // $stopwords = Stopword::all();
        //
        // foreach($stopwords as $stopword)
        // {
        //     $search = $this->BinarySearch($words, $stopword->word, 0, count($words)-1);
        //     if($search > -1)
        //     {
        //         unset($words[$search]);
        //         $words = array_values($words);
        //     }
        // }
        //
        // $p_positive = $count_tweet_positive/$N;
        // $p_negative = $count_tweet_negative/$N;
        // $p_neutral = $count_tweet_neutral/$N;
        //
        // // size vocabulary
        // $v = count($words);

        // foreach($test as $tweet)
        // {
        //     // calculate positive
        //     foreach($tweet as $word)
        //     {
        //         $p_word = ($count_positive + 1)/(BagOfWord::countWord($word) + $v);
        //         $p_positive = $p_positive * $p_word;
        //     }
        //
        //     // calculate negative
        //     foreach($tweet as $word)
        //     {
        //         $p_word = ($count_negative + 1)/(BagOfWord::countWord($word) + $v);
        //         $p_negative = $p_negative * $p_word;
        //     }
        //
        //     // calculate neutral
        //     foreach($tweet as $word)
        //     {
        //         $p_word = ($count_ + 1)/(BagOfWord::countWord($word) + $v);
        //         $p_neutral = $p_neutral * $p_word;
        //     }
        //
        //     if($p_positive > $p_negative && $p_positive > $p_neutral)
        //         return 1;   // positive
        //     else if($p_negative > $p_positive && $p_negative > $p_neutral)
        //         return 2;   // negative
        //     else
        //         return 3;   // neutral
        // }

        $time_elapsed_secs = microtime(true) - $start;

        echo ' '.$time_elapsed_secs;
    }
}
