<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Http\Requests;

class TokenizingController extends Controller
{
    public function index()
    {
    	$words = BagOfWord::all();

    	return view('tokenizing.index')
    		->with('words', $words);
    }

    public function tokenize()
    {

    }
}
