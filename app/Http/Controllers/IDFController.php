<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Models\IDF;
use App\Http\Requests;
use App\Models\FeatureSelection;
use App\Models\NegationHandlingProcess;
use App\Models\Tweet;
use DB;
use Redirect;
use Response;

class IDFController extends Controller
{
    public function index()
    {
    	$words = BagOfWord::all();
        $process = FeatureSelection::get();

    	return view('idf.index')
            ->with('process', $process)
    		->with('words', $words);
    }

    public function getTFIDF()
    {
        $words = BagOfWord::all();
        return Response::json($words);
    }

    public function process()
    {
    	$words = BagOfWord::all();
        $tweet = Tweet::all();

    	$N = count($tweet);

        $negation_count = 0;

    	// hitung jumlah token dalam bag of words
        DB::beginTransaction();
    	foreach($words as $word)
    	{
    		$bow = BagOfWord::find($word->id);
    		// $bow->count_positive = BagOfWord::countPositive($word->id);
    		// $bow->count_negative = BagOfWord::countNegative($word->id);
    		// $bow->count_neutral = BagOfWord::countNeutral($word->id);
    		// $bow->count = BagOfWord::count($word->id);
    		$bow->idf = log10($N/$bow->count_tweet);

    		$bow->save();

            //hitung jumlah term negasi
            if(strpos($word, "tidak_") !== false)
                $negation_count++;
    	}

        $negation = NegationHandlingProcess::get();
        if(!empty($negation))
        {
            if(!$negation->evaluated)
            {
                $count = NegationHandlingProcess::find($negation->id);
                $count->count_negated_term = $negation_count;
                $count->save();
            }
        }

        DB::commit();

    	return Redirect::to('dashboard/idf');
    }

    public function idfselection($bow, $data)
    {
        $selection = $data;

        $negation = 0;

        foreach($bow as $bag)
        {
            if($bag->idf <= $selection)
            {
                if(strpos($bag->word, "tidak_") !== false)
                    $negation++;
                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }

        return $negation;
    }

    public function tfselection($bow, $data)
    {
        $selection = $data;

        $negation = 0;

        foreach($bow as $bag)
        {
            if($bag->count <= $selection)
            {
                if(strpos($bag->word, "tidak_") !== false)
                    $negation++;
                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }

        return $negation;
    }

    public function dfselection($bow, $data)
    {
        $selection = $data;

        $negation = 0;

        foreach($bow as $bag)
        {
            if($bag->count_tweet <= $selection)
            {
                if(strpos($bag->word, "tidak_") !== false)
                {
                    $negation++;
                }

                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }

        return $negation;
    }

    public function cutFeature(Request $request)
    {
        $start = microtime(true);

        $df = $request->input('df');
        $idf = $request->input('idf');

        $bow = BagOfWord::all();
        $bow_before = count($bow);

        DB::beginTransaction();

        $selection = new FeatureSelection;
        $selection->term_positive = BagOfWord::countWordPositive();
        $selection->term_negative = BagOfWord::countWordNegative();
        $selection->term_neutral = BagOfWord::countWordNeutral();


        $negated_df = $this->dfselection($bow, $df);
        $selection->df = $df;
        $after_df = count(BagOfWord::all());
        $bow_after_df = $bow_before - $after_df;
        $selection->truncated_term_df = $bow_after_df;

        $negated_idf = $this->idfselection($bow, $idf);
        $selection->idf = $idf;
        $after_idf = count(BagOfWord::all());
        $bow_after_idf = $after_df - $after_idf;
        $selection->truncated_term_idf = $bow_after_idf;

        $negation = NegationHandlingProcess::get();
        if(!empty($negation))
        {
            if(empty($negation->truncated_term_selection))
            {
                $count = NegationHandlingProcess::find($negation->id);
                $count->truncated_term_selection = $negated_df + $negated_idf;
                $count->save();
            }
        }

        $selection->truncated_term_positive = BagOfWord::countWordPositive();
        $selection->truncated_term_negative = BagOfWord::countWordNegative();
        $selection->truncated_term_neutral = BagOfWord::countWordNeutral();

        $time_elapsed_secs = microtime(true) - $start;
        $selection->process_time = $time_elapsed_secs;
        $selection->save();

        DB::commit();

        return Redirect::to('dashboard/idf');
    }
}
