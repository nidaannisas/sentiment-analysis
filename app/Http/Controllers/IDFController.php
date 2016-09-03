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
    		$bow->count = BagOfWord::count($word->id);
    		$bow->idf = $N/$bow->count;

    		$bow->save();
    	}

    	return Redirect::to('dashboard/idf');
    }
}
