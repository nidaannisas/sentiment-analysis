<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TweetResult;
use App\Models\BagOfWord;
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
        if($one > $two && $one > $three)
            return 1;   // positive
        else if($two > $one && $two > $three)
            return 2;   // negative
        else
            return 3;   // neutral
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

        // positive_negative result positive
        $pn_p = 0;
        $pn_n = 0;
        $pn_o = 0;

        $po_p = 0;
        $po_n = 0;
        $po_o = 0;

        $np_p = 0;
        $np_n = 0;
        $np_o = 0;

        $no_p = 0;
        $no_n = 0;
        $no_o = 0;

        $op_p = 0;
        $op_n = 0;
        $op_o = 0;

        $on_p = 0;
        $on_n = 0;
        $on_o = 0;

        foreach($tweets as $tweet)
        {
            $naive_bayes = $this->naiveBayesEvaluate($tweet->tweet, $data);
            $rocchio = $this->rocchio($tweet->tweet);

            //echo $naive_bayes.' '.$rocchio.'<br />';

            // positive negative
            if($naive_bayes == 1 && $rocchio == 2)
            {
                if($tweet->sentiment_id == 1)
                    $pn_p++;
                else if($tweet->sentiment_id == 2)
                    $pn_n++;
                else
                    $pn_o++;
            }

            // positive neutral
            if($naive_bayes == 1 && $rocchio == 3)
            {
                if($tweet->sentiment_id == 1)
                    $po_p++;
                else if($tweet->sentiment_id == 2)
                    $po_n++;
                else
                    $po_o++;
            }

            // negative positive
            if($naive_bayes == 2 && $rocchio == 1)
            {
                if($tweet->sentiment_id == 1)
                    $np_p++;
                else if($tweet->sentiment_id == 2)
                    $np_n++;
                else
                    $np_o++;
            }

            // negative neutral
            if($naive_bayes == 2 && $rocchio == 3)
            {
                if($tweet->sentiment_id == 1)
                    $no_p++;
                else if($tweet->sentiment_id == 2)
                    $no_n++;
                else
                    $no_o++;
            }

            // neutral positivge
            if($naive_bayes == 3 && $rocchio == 1)
            {
                if($tweet->sentiment_id == 1)
                    $op_p++;
                else if($tweet->sentiment_id == 2)
                    $op_n++;
                else
                    $op_o++;
            }

            // neutral negative
            if($naive_bayes == 3 && $rocchio == 2)
            {
                if($tweet->sentiment_id == 1)
                    $on_p++;
                else if($tweet->sentiment_id == 2)
                    $on_n++;
                else
                    $on_o++;
            }
        }

        DB::beginTransaction();

        // Rules Positive Negative
        $rules = new NRRules;
        $rules->naive_bayes = 1;
        $rules->rocchio = 2;
        $rules->result = $this->max($pn_p, $pn_n, $pn_o);
        $rules->save();

        //echo $pn_p.' '.$pn_n.' '.$pn_o.'<br />';

        // Rules Positive Neutral
        $rules = new NRRules;
        $rules->naive_bayes = 1;
        $rules->rocchio = 3;
        $rules->result = $this->max($po_p, $po_n, $po_o);
        $rules->save();

        //echo $po_p.' '.$po_n.' '.$po_o.'<br />';

        // Rules Negative Positive
        $rules = new NRRules;
        $rules->naive_bayes = 2;
        $rules->rocchio = 1;
        $rules->result = $this->max($np_p, $np_n, $np_o);
        $rules->save();

        //echo $np_p.' '.$np_n.' '.$np_o.'<br />';

        // Rules Negative Neutral
        $rules = new NRRules;
        $rules->naive_bayes = 2;
        $rules->rocchio = 3;
        $rules->result = $this->max($no_p, $no_n, $no_o);
        $rules->save();

        //echo $no_p.' '.$no_n.' '.$no_o.'<br />';

        // Rules Neutral Positive
        $rules = new NRRules;
        $rules->naive_bayes = 3;
        $rules->rocchio = 1;
        $rules->result = $this->max($op_p, $op_n, $op_o);
        $rules->save();

        //echo $op_p.' '.$op_n.' '.$op_o.'<br />';

        // Rules Neutral Negative
        $rules = new NRRules;
        $rules->naive_bayes = 3;
        $rules->rocchio = 2;
        $rules->result = $this->max($on_p, $on_n, $on_o);
        $rules->save();

        //echo $on_p.' '.$on_n.' '.$on_o.'<br />';

        DB::commit();

        return Redirect::to('dashboard/nr-rules');
    }

}
