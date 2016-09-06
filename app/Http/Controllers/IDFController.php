<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Models\IDF;
use App\Http\Requests;

use Redirect;

class IDFController extends Controller
{
    public function index()
    {
    	$words = BagOfWord::all();

    	return view('idf.index')
    		->with('words', $words);
    }

    public function process()
    {
    	$words = BagOfWord::all();

    	$N = count($words);

    	// hitung jumlah token dalam bag of words
    	foreach($words as $word)
    	{
    		$bow = BagOfWord::find($word->id);
    		$bow->count_positive = BagOfWord::countPositive($word->id);
    		$bow->count_negative = BagOfWord::countNegative($word->id);
    		$bow->count_neutral = BagOfWord::countNeutral($word->id);
    		$bow->count = BagOfWord::count($word->id);
    		$bow->idf = log10($N/$bow->count);

    		$bow->save();
    	}

    	return Redirect::to('dashboard/idf');
    }
}
