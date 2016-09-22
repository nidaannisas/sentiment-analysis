<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\BagOfWord;
use App\Models\TDM;
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

        foreach($tweets as $tweet)
        {
            // remove except letter
            //$tweet->tweet = preg_replace('#^https?://*/', '', $tweet->tweet);
            $tweet->tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/', '#^https?([a-zA-Z_ -]*)#'), array('', ' ', ''), $tweet->tweet);


            // to lower
            $tweet->tweet = strtolower($tweet->tweet);

            $words = array();
            $delim = " \n.,;-()";
            $tok = strtok($tweet->tweet, $delim);
            while ($tok !== false) 
            {
                $words[] = $tok;
                $tok = strtok($delim);
            }

            // unique di dalam dokumen
            //$words = array_unique($words);

            // unique antar dokument
            // tidak ada redundant kata dalam tdm

            DB::beginTransaction();

            foreach($words as $word)
            {
                $kata = BagOfWord::search($word);

                if(empty($kata))
                {
                    $save = new BagOfWord;
                    $save->word = $word;
                    $save->count = 1;

                    if($tweet->sentiment_id == 1)
                    {
                        $save->count_positive = 1;
                    }
                    else if($tweet->sentiment_id == 2)
                    {
                        $save->count_negative = 1;
                    }
                    else
                    {
                        $save->count_neutral = 1;
                    }

                    $save->save();

                    // $tdm = new TDM;
                    // $tdm->tweet_id = $tweet->id;
                    // $tdm->token_id = $save->id;
                    // $tdm->save();


                }
                else
                {
                    // $tdm = new TDM;
                    // $tdm->tweet_id = $tweet->id;
                    // $tdm->token_id = $kata->id;
                    // $tdm->save();
                    $save = BagOfWord::find($kata->id);
                    $save->count = $save->count + 1;

                    if($tweet->sentiment_id == 1)
                    {
                        $save->count_positive = $save->count_positive + 1;
                    }
                    else if($tweet->sentiment_id == 2)
                    {
                        $save->count_negative = $save->count_negative + 1;
                    }
                    else
                    {
                        $save->count_neutral = $save->count_neutral + 1;
                    }

                    $save->save();
                }
            }

            DB::commit();
        }

        return Redirect::to('dashboard/tokenizing');
    }
}
