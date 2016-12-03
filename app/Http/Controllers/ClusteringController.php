<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Models\BagOfWord;
use App\Models\TweetResult;
use App\Http\Requests;
use Excel;
use Redirect;
use Input;

class ClusteringController extends TokenizingController
{
    public function index()
    {
    	$sentiments = Sentiment::all();
    	$tweets = TweetResult::all();

    	return view('clustering.index')
    		->with('sentiments', $sentiments)
    		->with('tweets', $tweets);
    }

    public function process()
    {
        $tweets = TweetResult::all();

        foreach($tweets as $key => $tweet)
        {
            $tweets[$key]->tweet = $this->tokenizeEvaluation($tweets[$key]->tweet);
            $tweets[$key]->tweet_unique = array_count_values($tweets[$key]->tweet);
        }

        $bows = BagOfWord::getBagOfWords();
        $bows = $this->quicksort_multidimension_object_word($bows);

        // foreach($bows as $bow)
        // {
        //     $normal = $this->BinarySearchObjectWord($bows, $bow->word, 0, count($bows)-1);
        //
        //     if($normal == -1)
        //         echo $bow->word.'<br />';
        //     //if($bow->word == "partai") echo $bow->word;
        // }

        //var_dump($bows[0]->word);
        // echo $normal = $this->BinarySearchObjectWord($bows, "partai", 0, count($bows)-1);
        var_dump($tweets[0]->tweet_unique);
        // echo $result = isset($tweets[0]->tweet_unique["partai"]) ? $tweets[0]->tweet_unique["partai"] : 0;


        $dtm = array();

        foreach($tweets as $i => $tweet)
        {
            foreach($bows as $j => $bow)
            {
                $result = isset($tweets[$i]->tweet_unique[$bows[$j]->word]) ? $tweets[$i]->tweet_unique[$bows[$j]->word] : 0;
                $dtm[$i][$j] = $result;
            }
        }

        for($i = 0; $i < 1; $i++)
        {
            foreach($bows as $j => $bow)
            {
                echo $dtm[$i][$j].' ';
            }
            echo '<br />';
        }
    }

}
