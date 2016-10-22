<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NormalizationWord;
use App\Models\BagOfWord;
use App\Models\Tweet;
use App\Http\Requests;
use DB;
use Redirect;

class WordNormalizationController extends Controller
{
    public function index()
    {
    	$normalizations = NormalizationWord::all();

    	return view('word-normalization.index')
    		->with('normalizations', $normalizations);
    }

    public function process()
    {
        $normalizations = NormalizationWord::getNormalizationWords();
        $normalizations = $this->quicksort_multidimension_object_word($normalizations);

        $tweets = Tweet::all();

        foreach($tweets as $tweet)
        {
            // to lower
            $tweet->tweet = strtolower($tweet->tweet);

            $words = array();
            $delim = " \n.,;-()";
            $tok = strtok($tweet->tweet, $delim);
            while ($tok !== false)
            {
                $normal = $this->BinarySearchObjectWord($normalizations, $tok, 0, count($normalizations)-1);

                if($normal != -1)
                    $tok = $normalizations[$normal]->normal_word;
                $words[] = $tok;
                $tok = strtok($delim);
            }

            $tweet_normal = Tweet::find($tweet->id);
            $tweet_normal->tweet = implode(" ",$words);
            $tweet_normal->save();
        }

        return Redirect::to('dashboard/tweets');
    }

}
