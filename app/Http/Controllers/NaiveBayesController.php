<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\BagOfWord;
use App\Models\Stopword;
use App\Models\NormalizationWord;
use App\Models\IDF;
use App\Http\Requests;

use Redirect;

class NaiveBayesController extends StopwordController
{
    public function index()
    {
    	$tweets = TweetTest::orderBy('id', 'DESC')->get();

    	return view('naive-bayes.index')
    		->with('tweets', $tweets);
    }

    public function classify(Request $request)
    {
    	$tweet = $request->input('tweet');
        $stopwords = Stopword::all();
        $normalizations = NormalizationWord::all();

    	$tweets = new TweetTest;
    	$tweets->tweet = $tweet;
        $tweets->sentiment_id = $this->naiveBayes($tweet, $normalizations, $stopwords);
    	$tweets->save();

    	//return Redirect::to('dashboard/naive-bayes');
    }

    // public function tokenize($tweet)
    // {
    //     // remove except letter
    //     $tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/'), array('', ' ', ''), $tweet);
    //
    //     // to lower
    //     $tweet = strtolower($tweet);
    //
    //     $words = array();
    //     $delim = " \n.,;-()";
    //     $tok = strtok($tweet, $delim);
    //     while ($tok !== false)
    //     {
    //         $words[] = $tok;
    //         $tok = strtok($delim);
    //     }
    //
    //     // unique di dalam dokumen
    //     //$words = array_unique($words);
    //
    //     return $words;
    // }
    //
    // public function normalizeWord($tweet, $normalizations)
    // {
    //     foreach($tweet as $key => $word)
    //     {
    //         $search = $this->BinarySearchObjectWord($normalizations, $word, 0, count($normalizations)-1);
    //
    //         $data = NormalizationWord::search($word);
    //         if($search == -1)
    //         {
    //             if(!empty($data))
    //                 echo $search.' '.$word.'<br />';
    //         }
    //         if($search > -1)
    //         {
    //             $tweet[$key] = $normalizations[$search]->normal_word;
    //         }
    //     }
    //
    //     return $tweet;
    // }

    public function stopwordRemoval($tweet, $stopwords)
    {
        foreach($tweet as $key => $word)
        {
            $search = $this->BinarySearchObjectWord($stopwords, $word, 0, count($stopwords)-1);
            //echo $word.' '.$search;
            if($search > -1)
            {
                //echo 'ketemu';
                unset($tweet[$key]);
            }
        }

        return $tweet;
    }

    public function naiveBayes($tweet, $normalizations, $stopwords)
    {
        // tokenize tweet
        $tweet = $this->tokenize($tweet);

        // normalize word
        $tweet = $this->normalizeWord($tweet, $normalizations);

        // stopword removal
        //$tweet = $this->stopwordRemoval($tweet, $stopwords);



        // jumlah dokumen
        $N = count(Tweet::getTrain());

        $p_positive = Tweet::countPositiveTrain()/$N;
        $p_negative = Tweet::countNegativeTrain()/$N;
        $p_neutral = Tweet::countNeutralTrain()/$N;

        // size vocabulary
        $v = count(BagOfWord::all());

        // calculate positive
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countPositiveWord($word) + 1)/(BagOfWord::countWordPositive() + $v);
            $p_positive = $p_positive * $p_word;
        }

        // calculate negative
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countNegativeWord($word) + 1)/(BagOfWord::countWordNegative() + $v);
            $p_negative = $p_negative * $p_word;
        }

        // calculate neutral
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countNeutralWord($word) + 1)/(BagOfWord::countWordNeutral() + $v);
            $p_neutral = $p_neutral * $p_word;
        }

        if($p_positive > $p_negative && $p_positive > $p_neutral)
            return 1;   // positive
        else if($p_negative > $p_positive && $p_negative > $p_neutral)
            return 2;   // negative
        else
            return 3;   // neutral
    }

    public function naiveBayesEvaluate($tweet)
    {
        // tokenize tweet
        $tweet = $this->tokenizeEvaluation($tweet);

        // jumlah dokumen
        $N = count(Tweet::getTrain());

        $p_positive = Tweet::countPositiveTrain()/$N;
        $p_negative = Tweet::countNegativeTrain()/$N;
        $p_neutral = Tweet::countNeutralTrain()/$N;

        // size vocabulary
        $v = count(BagOfWord::all());

        // calculate positive
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countPositiveWord($word) + 1)/(BagOfWord::countWordPositive() + $v);
            $p_positive = $p_positive * $p_word;
        }

        // calculate negative
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countNegativeWord($word) + 1)/(BagOfWord::countWordNegative() + $v);
            $p_negative = $p_negative * $p_word;
        }

        // calculate neutral
        foreach($tweet as $word)
        {
            $p_word = (BagOfWord::countNeutralWord($word) + 1)/(BagOfWord::countWordNeutral() + $v);
            $p_neutral = $p_neutral * $p_word;
        }

        if($p_positive > $p_negative && $p_positive > $p_neutral)
            return 1;   // positive
        else if($p_negative > $p_positive && $p_negative > $p_neutral)
            return 2;   // negative
        else
            return 3;   // neutral
    }
}
