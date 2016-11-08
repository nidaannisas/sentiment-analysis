<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Models\IDF;
use App\Http\Requests;
use App\Models\TDM;
use App\Models\Tweet;
use DB;
use Redirect;
use Response;

class IDFController extends Controller
{
    public function index()
    {
    	$words = BagOfWord::all();

    	return view('idf.index')
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
    	}
        DB::commit();

    	return Redirect::to('dashboard/idf');
    }

    public function selection(Request $request)
    {
        // bagodword sama tdm diahpus
        $selection = $request->input('selection');
        $bow = BagOfWord::all();

        DB::beginTransaction();
        foreach($bow as $bag)
        {
            if($bag->idf <= $selection)
            {
                // remove tdm
                // $tdm = TDM::all();
                // foreach($tdm as $t)
                // {
                //     if($t->token_id == $bag->id)
                //     {
                //         TDM::destroy($t->id);
                //     }
                // }

                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }
        DB::commit();

        return Redirect::to('dashboard/idf');
    }

    public function tfselection(Request $request)
    {
        // bagodword sama tdm diahpus
        $selection = $request->input('selection');
        $bow = BagOfWord::all();

        DB::beginTransaction();
        foreach($bow as $bag)
        {
            if($bag->count <= $selection)
            {
                // remove tdm
                // $tdm = TDM::all();
                // foreach($tdm as $t)
                // {
                //     if($t->token_id == $bag->id)
                //     {
                //         TDM::destroy($t->id);
                //     }
                // }

                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }
        DB::commit();

        return Redirect::to('dashboard/idf');
    }

    public function dfselection(Request $request)
    {
        // bagodword sama tdm diahpus
        $selection = $request->input('selection');
        $bow = BagOfWord::all();

        DB::beginTransaction();
        foreach($bow as $bag)
        {
            if($bag->count_tweet <= $selection)
            {
                // remove tdm
                // $tdm = TDM::all();
                // foreach($tdm as $t)
                // {
                //     if($t->token_id == $bag->id)
                //     {
                //         TDM::destroy($t->id);
                //     }
                // }

                // remove bow
                BagOfWord::destroy($bag->id);
            }
        }
        DB::commit();

        return Redirect::to('dashboard/idf');
    }
}
