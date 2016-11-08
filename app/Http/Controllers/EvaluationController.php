<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\NormalizationWord;
use App\Models\Stopword;
use App\Http\Requests;

use Redirect;

class EvaluationController extends NaiveBayesController
{
    public function index()
    {
    	$tweets = TweetTest::orderBy('id', 'DESC')->get();

    	return view('evaluation.index')
    		->with('tweets', $tweets);
    }

    public function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        return $temp_array;
    }

    // public function tokenizing($tweets)
    // {
    //     $words = array();
    //     $count_positive = 0;
    //     $count_negative = 0;
    //     $count_neutral = 0;
    //
    //     $count_tweet_positive = 0;
    //     $count_tweet_negative = 0;
    //     $count_tweet_neutral = 0;
    //
    //     $i = 0;
    //     foreach($tweets as $tweet)
    //     {
    //         if($tweet->sentiment_id == 1)
    //             $count_tweet_positive++;
    //         else if($tweet->sentiment_id == 2)
    //             $count_tweet_negative++;
    //         else
    //             $count_tweet_neutral++;
    //
    //         // remove except letter
    //         //$tweet->tweet = preg_replace('#^https?://*/', '', $tweet->tweet);
    //         $tweet->tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/', '#^https?([a-zA-Z_ -]*)#'), array('', ' ', ''), $tweet->tweet);
    //
    //         // to lower
    //         $tweet->tweet = strtolower($tweet->tweet);
    //
    //         $delim = " \n.,;-()";
    //         $tok = strtok($tweet->tweet, $delim);
    //
    //         while ($tok !== false)
    //         {
    //             $words[$i]['term'] = $tok;
    //             $tok = strtok($delim);
    //
    //             if($tweet->sentiment_id == 1)
    //             {
    //                 $words[$i]['count_positive'] = $count_positive + 1;
    //             }
    //             else if($tweet->sentiment_id == 2)
    //             {
    //                 $words[$i]['count_negative'] = $count_negative + 1;
    //             }
    //             else
    //             {
    //                 $words[$i]['count_neutral'] = $count_neutral + 1;
    //             }
    //
    //             $i++;
    //         }
    //
    //
    //     }
    //
    //     $words = $this->quicksort_multidimension($words, 'term');
    //
    //
    //     // $words = array_values($words);
    //
    //     $result = array("words" => $words,
    //                     "count_positive" => $count_positive,
    //                     "count_negative" => $count_negative,
    //                     "count_neutral" => $count_neutral,
    //                     "count_tweet_positive" => $count_tweet_positive = 0,
    //                     "count_tweet_negative" => $count_tweet_negative = 0,
    //                     "count_tweet_neutral" => $count_tweet_neutral = 0,
    //                 );
    //
    //     return $result;
    // }
    //
    // public function normalization($tweets)
    // {
    //     $normalizations = NormalizationWord::all();
    //
    //     foreach($normalizations as $normalization)
    //     {
    //         $search = $this->BinarySearch($tweets, $normalization->word, 0, count($tweets)-1);
    //         if($search > -1)
    //         {
    //             $tweets[$search] = $normalization->normal_word;
    //         }
    //
    //     }
    //
    //     return $tweets;
    // }
    //
    // public function stopword($tweets)
    // {
    //     $stopwords = Stopword::all();
    //
    //     foreach($stopwords as $stopword)
    //     {
    //         $search = $this->BinarySearch($tweets, $stopword->word, 0, count($tweets)-1);
    //         if($search > -1)
    //         {
    //             unset($tweets[$search]);
    //             $tweets = array_values($tweets);
    //         }
    //     }
    //
    //     return $tweets;
    // }
    //
    // public function naiveBayes($data)
    // {
    //     $p_positive = $count_tweet_positive/$N;
    //     $p_negative = $count_tweet_negative/$N;
    //     $p_neutral = $count_tweet_neutral/$N;
    //
    //     // size vocabulary
    //     $v = count($words);
    //
    //     foreach($test as $tweet)
    //     {
    //         // calculate positive
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_positive + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_positive = $p_positive * $p_word;
    //         }
    //
    //         // calculate negative
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_negative + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_negative = $p_negative * $p_word;
    //         }
    //
    //         // calculate neutral
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_ + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_neutral = $p_neutral * $p_word;
    //         }
    //
    //         if($p_positive > $p_negative && $p_positive > $p_neutral)
    //             return 1;   // positive
    //         else if($p_negative > $p_positive && $p_negative > $p_neutral)
    //             return 2;   // negative
    //         else
    //             return 3;   // neutral
    //     }
    // }

    public function evaluateKFold()
    {
        $start = microtime(true);

        $tweets = Tweet::getTweets();
        $train = array_slice($tweets,0,13);
        $test = array_slice($tweets,8,10);
        $N = count($train);

        // tokenizing
        $tokenizing = $this->tokenizing($tweets);

        //foreach($tokenizing['words'] as $t) echo $t['term'].'<br />';
        // $words = $tokenizing['words'];
        //
        // $count_positive = $tokenizing['count_positive'];
        // $count_negative = $tokenizing['count_negative'];
        // $count_neutral = $tokenizing['count_neutral'];
        //
        // $count_tweet_positive = $tokenizing['count_tweet_positive'];
        // $count_tweet_negative = $tokenizing['count_tweet_negative'];
        // $count_tweet_neutral = $tokenizing['count_tweet_neutral'];
        //
        // // sort bag of words
        // $words = $this->quicksort($words);
        //
        // // words normalization
        // $words = $this->normalization($words);
        //
        // // stopwords
        // $words = $this->stopword($words);
        //
        // // Seleksi fitur gimana ?
        //
        // // naive bayes


        $time_elapsed_secs = microtime(true) - $start;

        echo ' '.$time_elapsed_secs;
    }

    public function evaluate()
    {
        $start = microtime(true);

        $tweets = Tweet::getTrain();
        $normalizations = NormalizationWord::getNormalizationWords();
        $stopwords = Stopword::getStopwords();

        $count_default_class_positive = 0;
        $count_default_class_negative = 0;
        $count_default_class_neutral = 0;

        $count_class_positive = 0;
        $count_class_negative = 0;
        $count_class_neutral = 0;

        $right_class = 0;
        $right_class_positive = 0;
        $right_class_negative = 0;
        $right_class_neutral = 0;
        $N = count($tweets);

        foreach($tweets as $tweet)
        {
            $class = $this->naiveBayesEvaluate($tweet->tweet, $stopwords);

            if($tweet->sentiment_id == 1)
                $count_default_class_positive++;
            else if($tweet->sentiment_id == 2)
                $count_default_class_negative++;
            else
                $count_default_class_neutral++;

            if($class == 1)
                $count_class_positive++;
            else if($class == 2)
                $count_class_negative++;
            else
                $count_class_neutral++;

            if($class == $tweet->sentiment_id)
            {
                $right_class++;

                if($class == 1)
                    $right_class_positive++;
                else if($class == 2)
                    $right_class_negative++;
                else
                    $right_class_neutral++;
            }
        }

        $accuracy = ($right_class/$N)*100;
        $precision_positive = ($right_class_positive/$count_class_positive)*100;
        $precision_negative = ($right_class_negative/$count_class_negative)*100;
        $precision_neutral = ($right_class_neutral/$count_class_neutral)*100;

        $recall_positive = ($right_class_positive/$count_default_class_positive)*100;
        $recall_negative = ($right_class_negative/$count_default_class_negative)*100;
        $recall_neutral = ($right_class_neutral/$count_default_class_neutral)*100;

        $time_elapsed_secs = microtime(true) - $start;

        echo 'Waktu  : '.$time_elapsed_secs.'<br />';

        echo 'Accuracy : '.$accuracy.'<br />';
        echo 'Precision positive : '.$precision_positive.'<br />';
        echo 'Precision negative : '.$precision_negative.'<br />';
        echo 'Precision neutral : '.$precision_neutral.'<br />';
        echo 'Recall positive : '.$recall_positive.'<br />';
        echo 'Recall negative : '.$recall_negative.'<br />';
        echo 'Recall neutral : '.$recall_neutral.'<br />';
    }
}
