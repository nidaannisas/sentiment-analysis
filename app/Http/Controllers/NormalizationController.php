<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stopword;
use App\Models\BagOfWord;
use App\Http\Requests;

use Redirect;

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

    public function importtxt(Request $request)
    {
    	$file= $request->file('import');

	    $fopen = fopen($file, "r");

	    $fread = fread($fopen,filesize($file));

	    fclose($fopen);

	    $remove = "\n";

	    $split = explode($remove, $fread);

	    $array[] = null;
	    $tab = "\t";

	    foreach ($split as $string)
	    {
	        $row = explode($tab, $string);
	        array_push($array,$row);
	    }

	    for($i = 1; $i < count($array); $i++)
	    {
	    	$stop = new Stopword;
	    	$stop->word = $array[$i][0];
	    	$stop->save();
	    }

	    return Redirect::to('dashboard/stopwords');
    }

    public function process()
    {
    	$stopwords = Stopword::all();

    	foreach($stopwords as $stopword)
    	{
    		$word = BagOfWord::search($stopword->word);

    		if(!empty($word))
    			BagOfWord::destroy($word->id);
    	}

    	return Redirect::to('dashboard/tokenizing');
    }
}
