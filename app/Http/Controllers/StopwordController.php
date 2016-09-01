<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stopword;
use App\Http\Requests;

class StopwordController extends Controller
{
    public function index()
    {
    	$stopwords = Stopword::all();

    	return view('stopwords.index')
    		->with('stopwords', $stopwords);
    }

    public function store(Request $request)
    {
    	$word = $request->input('word');

    	$stop = new Stopword;
    	$stop->word = $word;
    	$stop->save();

    	return Redirect::to('dashboard/stopwords');
    }
}
