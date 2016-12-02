<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetResult;
use App\Models\BagOfWord;
use App\Models\BagOfWordTest;
use App\Models\TokenizingProcess;
use App\Http\Requests;
use Redirect;
use DB;

class TokenizingController extends Controller
{
    public function index()
    {
        $words = BagOfWord::all();
        $process = TokenizingProcess::get();

        return view('tokenizing.index')
            ->with('process', $process)
            ->with('words', $words);
    }

    public function tokenizingWord()
    {
        $tweets = TweetResult::all();

        return view('tokenizing.tokenizing-word')
            ->with('tweets', $tweets);
    }

    public function replace4byte($string)
    {
        return preg_replace('%(?:
              \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )%xs', '', $string);
    }

    public function tokenizeWord()
    {
        $start = microtime(true);

        $tweets = Tweet::all();

        // delete all field
        DB::table('tweets_result')->delete();

        foreach($tweets as $tweet)
        {
            // remove link
            $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
            $tweet->tweet =  preg_replace($regex, ' ', $tweet->tweet);

            // remove char except letter
            $tweet->tweet =  preg_replace(array('/[^a-zA-Z -]/', '/[ -]+/', '/^-|-$/'), array(' ', ' '), $tweet->tweet);

            // to lower
            $tweet->tweet = strtolower($tweet->tweet);

            //echo $tweet->id.'<br />';

            //$tweet_normal = Tweet::find($tweet->id);
            $tweet_normal = new TweetResult;
            $tweet_normal->tweet = $tweet->tweet;
            $tweet_normal->sentiment_id = $tweet->sentiment_id;
            $tweet_normal->negated = $tweet->negated;
            $tweet_normal->type = $tweet->type;
            $tweet_normal->save();
        }

        $time_elapsed_secs = microtime(true) - $start;

        return Redirect::to('dashboard/tokenizing-word');
    }

    public function tokenizeTweetTest()
    {
        // delete all field
        DB::table('bag-of-words-test')->delete();

        $tweets = TweetResult::getTest();

        foreach($tweets as $tweet)
        {
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
        $start = microtime(true);

        // delete all field
        DB::table('bag-of-words')->delete();

        $tweets = TweetResult::getTrain();

        foreach($tweets as $tweet)
        {
            $words = array();
            $delim = " \n.,;-()";
            $tok = strtok($tweet->tweet, $delim);
            while ($tok !== false)
            {
                $words[] = $tok;
                $tok = strtok($delim);
            }

            // variable count unique words
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
                }
                else
                {
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

        $time_elapsed_secs = microtime(true) - $start;

        $tokenizing_process = new TokenizingProcess;
        $tokenizing_process->count_token_train = count(BagOfWord::all());
        $tokenizing_process->process_time = $time_elapsed_secs;
        $tokenizing_process->save();
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
