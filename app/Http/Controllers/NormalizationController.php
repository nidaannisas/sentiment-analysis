<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NormalizationWord;
use App\Http\Requests;

use Redirect;

class NormalizationController extends Controller
{
    public function index()
    {
    	$normalizations = NormalizationWord::all();

    	return view('normalization.index')
    		->with('normalizations', $normalizations);
    }

}
