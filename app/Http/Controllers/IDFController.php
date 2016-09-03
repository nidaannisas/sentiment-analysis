<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Models\IDF;
use App\Http\Requests;

class IDFController extends Controller
{
    public function index()
    {
    	$words = BagOfWord::all();

    	// hitung jumlah token dalam bag of words
    	foreach($words as $word)
    	{
    		$word->count = BagOfWord::count($word->id);
    	}

    	return view('idf.index')
    		->with('words', $words);
    }

    public function process()
    {

    }
}
