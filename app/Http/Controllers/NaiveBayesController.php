<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\BagOfWord;
use App\Models\Stopword;
use App\Models\NormalizationWord;
use App\Http\Requests;

use Redirect;

class NaiveBayesController extends Controller
{
    public function index()
    {
    	$tweets = TweetTest::all();

    	return view('naive-bayes.index')
    		->with('tweets', $tweets);
    }

    public function classify(Request $request)
    {
    	$tweet = $request->input('tweet');

    	$tweets = new TweetTest;
    	$tweets->tweet = $tweet;
    	$tweets->save();

    	return Redirect::to('dashboard/naive-bayes');
    }

    public function tokenize($tweet)
    {
        // remove except letter
        $tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/'), array('', ' ', ''), $tweet);

        // to lower
        $tweet = strtolower($tweet);

        $words = array();
        $delim = " \n.,;-()";
        $tok = strtok($tweet, $delim);
        while ($tok !== false) 
        {
            $words[] = $tok;
            $tok = strtok($delim);
        }

        // unique di dalam dokumen
        $words = array_unique($words);

        return $words;
    }

    public function normalizeWord($tweet)
    {
        $normalizations = NormalizationWord::all();

        foreach($normalizations as $normalization)
        {
            foreach($tweet as $word)
            {
                if($word == $normalization->word)
                    $word = $normalization->normal_word;
            }
        }

        return $tweet;
    }

    public function stopwordRemoval($tweet)
    {
        $stopwords = Stopword::all();

        foreach($stopwords as $stopword)
        {
            foreach($tweet as $key => $word)
            {
                if($stopword->word == $word)
                    unset($tweet[$key]);
            }
        }

        return $tweet;
    }

    public function naiveBayes($tweet)
    {
        // jumlah dokumen
        $N = count(Tweet::all());               

        $p_positive = Tweet::countPositive()/$N;
        $p_negative = Tweet::countNegative()/$N;
        $p_neutral = Tweet::countNeutral()/$N;

        // size vocabulary
        $v = count(BagOfWord::all());
    }
}
