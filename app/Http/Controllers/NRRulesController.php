<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TweetResult;
use App\Models\NRRules;
use App\Http\Requests;
use Redirect;
use DB;

class NRRulesController extends EvaluationController
{
    public function index()
    {
    	$rules = NRRules::all();

    	return view('nr-rules.index')
    		->with('rules', $rules);
    }

    public function max($one, $two, $three)
    {
        
    }

    public function process()
    {
        $tweets = TweetResult::getTrain();

        $data = (object) array('N' => count(TweetResult::getTrain()),
                                'countPositiveTrain' => TweetResult::countPositiveTrain(),
                                'countNegativeTrain' => TweetResult::countNegativeTrain(),
                                'countNeutralTrain' => TweetResult::countNeutralTrain(),
                                'v' => count(BagOfWord::all()),
                                'countWordPositive' => BagOfWord::countWordPositive(),
                                'countWordNegative' => BagOfWord::countWordNegative(),
                                'countWordNeutral' => BagOfWord::countWordNeutral()
                            );

        foreach($tweets as $tweet)
        {
            $naive-bayes = $this->naiveBayesEvaluate($tweet->tweet, $data);
            $rocchio = $this->rocchio($tweet->tweet);

            // positive_negative result positive
            $pn_p = 0;
            $pn_n = 0;
            $pn_o = 0;

            if($naive-bayes == 1 && $rocchio == 2)
            {
                if($tweet->sentiment_id == 1)
                    $pn_p++;
                else if($tweet->sentiment_id == 2)
                    $pn_n++;
                else
                    $pn_o++;
            }
        }
    }

}
