<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\BagOfWord;
use App\Http\Requests;
use Redirect;
use DB;

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
        // underscore masih kehapus
        
        // delete all field
        DB::table('bag-of-words')->delete();

        $tweets = Tweet::all();

        $content = '';

        foreach($tweets as $tweet)
        {
            $content = $content.$tweet->tweet;
        }

        // remove except letter
        $content = preg_replace(array('/[^a-zA-Z -]/', '/[ -]+/', '/^-|-$/'), array('', ' ', ''), $content);

        // to lower
        $content = strtolower($content);
        // remove number
        //$content = preg_replace('/[0-9]+/', '', $content);

        $words = array();
        $delim = " \n.,;-()";
        $tok = strtok($content, $delim);
        while ($tok !== false) {
          $words[] = $tok;
          $tok = strtok($delim);
        }
        $unique_words = array_unique($words);

        //var_dump($unique_words);

        foreach($unique_words as $kata)
        {
            $save = new BagOfWord;
            $save->word = $kata;
            $save->idf = 0;
            $save->save();
        }

        return Redirect::to('dashboard/tokenizing');
    }
}
