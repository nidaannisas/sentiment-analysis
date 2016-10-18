<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NormalizationWord;
use App\Models\BagOfWord;
use App\Http\Requests;
use DB;
use Redirect;

class WordNormalizationController extends Controller
{
    public function index()
    {
    	$normalizations = NormalizationWord::all();

    	return view('word-normalization.index')
    		->with('normalizations', $normalizations);
    }

    public function process()
    {
        $normalizations = NormalizationWord::getNormalizationWords();
        $normalizations = $this->quicksort_multidimension_object_word($normalizations);

        foreach ($normalizations as $n)
        {
            echo $n->word.'<br />';
        }

        echo $normalizations[$this->BinarySearchObjectWord($normalizations, 'abis', 0, 5)];


        //return Redirect::to('dashboard/tweets');
    }

}
