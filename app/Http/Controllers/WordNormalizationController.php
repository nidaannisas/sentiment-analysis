<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NormalizationWord;
use App\Models\NormalizationProcess;
use App\Models\BagOfWord;
use App\Models\Tweet;
use App\Models\TweetResult;
use App\Http\Requests;
use DB;
use Redirect;

class WordNormalizationController extends Controller
{
    public function index()
    {
    	$normalizations = NormalizationWord::all();
        $tweets = TweetResult::all();
        $process = NormalizationProcess::get();

    	return view('word-normalization.index')
            ->with('tweets', $tweets)
            ->with('process', $process)
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
        $start = microtime(true);
        $count_normalization_train = 0;
        $count_normalization_test = 0;

        $normalizations = NormalizationWord::getNormalizationWords();
        $normalizations = $this->quicksort_multidimension_object_word($normalizations);

        $tweets = TweetResult::all();

        foreach($tweets as $tweet)
        {
            $words = array();
            $delim = " \n.,;-()";
            $tok = strtok($tweet->tweet, $delim);
            while ($tok !== false)
            {
                $normal = $this->BinarySearchObjectWord($normalizations, $tok, 0, count($normalizations)-1);

                if($normal != -1)
                {
                    $tok = $normalizations[$normal]->normal_word;
                    if($tweet->type == 'TRAIN')
                        $count_normalization_train++;
                    else
                        $count_normalization_test++;
                }

                $words[] = $tok;
                $tok = strtok($delim);
            }

            $kata = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', implode(" ",$words));

            $tweet_normal = TweetResult::find($tweet->id);
            $tweet_normal->tweet = $kata;
            $tweet_normal->save();
        }

        $time_elapsed_secs = microtime(true) - $start;

        $normalization_process = new NormalizationProcess;
        $normalization_process->count_normalization_train = $count_normalization_train;
        $normalization_process->count_normalization_test = $count_normalization_test;
        $normalization_process->process_time = $time_elapsed_secs;
        $normalization_process->save();

        return Redirect::to('dashboard/word-normalization');
    }

}
