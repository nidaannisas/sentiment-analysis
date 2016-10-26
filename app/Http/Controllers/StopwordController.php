<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stopword;
use App\Models\BagOfWord;
use App\Models\BagOfWordTest;
use App\Http\Requests;
use DB;

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

    public function stopwordTest()
    {
        $stopwords = Stopword::all();

        DB::beginTransaction();
    	foreach($stopwords as $stopword)
    	{
    		$word = BagOfWordTest::search($stopword->word);

    		if(!empty($word))
    			BagOfWordTest::destroy($word->id);
    	}
        DB::commit();
    }

    public function stopwordTrain()
    {
        $stopwords = Stopword::all();

        DB::beginTransaction();
    	foreach($stopwords as $stopword)
    	{
    		$word = BagOfWord::search($stopword->word);

    		if(!empty($word))
    			BagOfWord::destroy($word->id);
    	}
        DB::commit();
    }

    public function process()
    {
        $this->stopwordTrain();
        //$this->stopwordTest();

    	return Redirect::to('dashboard/tokenizing');
    }
}
