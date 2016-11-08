<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\BagOfWord;
use App\Models\BagOfWordTest;
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

    public function tokenizingWord()
    {
        $tweets = Tweet::all();

        return view('tokenizing.tokenizing-word')
            ->with('tweets', $tweets);
    }

    public function tokenizeWord()
    {
        $tweets = Tweet::all();

        foreach($tweets as $tweet)
        {
            // remove link
            $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
            $tweet->tweet =  preg_replace($regex, ' ', $tweet->tweet);

            // remove char except letter
            $tweet->tweet =  preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/'), array(' ', ' '), $tweet->tweet);

            // to lower
            $tweet->tweet = strtolower($tweet->tweet);

            $tweet_normal = Tweet::find($tweet->id);
            $tweet_normal->tweet = $tweet->tweet;
            $tweet_normal->save();
        }

        return Redirect::to('dashboard/tokenizing-word');
    }

    public function tokenizeTweetTest()
    {
        // delete all field
        DB::table('bag-of-words-test')->delete();

        $tweets = Tweet::getTest();

        foreach($tweets as $tweet)
        {
            // remove link
            $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
            $tweet->tweet =  preg_replace($regex, ' ', $tweet->tweet);

            // remove char except letter
            $tweet->tweet =  preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/'), array(' ', ' '), $tweet->tweet);

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

            $unique_words = array();

            DB::beginTransaction();

            foreach($words as $word)
            {
                $kata = BagOfWordTest::search($word);
                if(empty($kata))
                {
                    $save = new BagOfWordTest;
                    $save->word = $word;
                    $save->count = 1;

                    // unique word
                    if(!in_array($word, $unique_words))
                    {
                        $save->count_tweet = 1;
                        $unique_words[] = $word;
                    }

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
                    $save = BagOfWordTest::find($kata->id);

                    //var_dump($save);
                    $save->count = $save->count + 1;

                    // unique word
                    if(!in_array($word, $unique_words))
                    {
                        $save->count_tweet = $save->count_tweet + 1;
                        $unique_words[] = $word;
                    }

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
    }

    public function tokenizeTweetTrain()
    {
        // delete all field
        DB::table('bag-of-words')->delete();

        $tweets = Tweet::getTrain();

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

            $unique_words = array();

            DB::beginTransaction();

            foreach($words as $word)
            {
                $kata = BagOfWord::search($word);

                if(empty($kata))
                {
                    $save = new BagOfWord;
                    $save->word = $word;
                    $save->count = 1;

                    // unique word
                    if(!in_array($word, $unique_words))
                    {
                        $save->count_tweet = 1;
                        $unique_words[] = $word;
                    }

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

                    // unique word
                    if(!in_array($word, $unique_words))
                    {
                        $save->count_tweet = $save->count_tweet + 1;
                        $unique_words[] = $word;
                    }

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
    }

    public function tokenize()
    {
        //$this->tokenizeTweetTest();
        $this->tokenizeTweetTrain();

        return Redirect::to('dashboard/tokenizing');
    }

    // tokenizing for evaluation per tweet
    public function tokenizeEvaluation($tweet)
    {
        $words = array();

        $delim = " \n.,;-()";
        $tok = strtok($tweet, $delim);
        while ($tok !== false)
        {
            $words[] = $tok;
            $tok = strtok($delim);
        }

        return $words;
    }
}
