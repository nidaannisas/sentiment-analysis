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

    public function store(Request $request)
    {
        $word = $request->input('word');
        $normal_word = $request->input('normal_word');

        $normalization = new NormalizationWord;
        $normalization->word = $word;
        $normalization->normal_word = $normal_word;
        $normalization->save();

        return Redirect::to('dashboard/normalization');
    }

}
