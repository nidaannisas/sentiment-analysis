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

    public function replace4byte($string)
    {
        return preg_replace('%(?:
              \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )%xs', '', $string);
    }

    public function process()
    {
        $normalizations = NormalizationWord::getNormalizationWords();
        $normalizations = $this->quicksort_multidimension_object_word($normalizations);

        $tweets = Tweet::all();

        foreach($tweets as $tweet)
        {
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

            $kata = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', implode(" ",$words));

            $tweet_normal = Tweet::find($tweet->id);
            $tweet_normal->tweet = $kata;
            $tweet_normal->save();
        }

        return Redirect::to('dashboard/tweets');
    }

}
