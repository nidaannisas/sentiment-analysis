<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Http\Requests;
use Excel;
use Redirect;
use Input;

class TweetController extends Controller
{
    public function index()
    {
    	$sentiments = Sentiment::all();
    	$tweets = Tweet::all();

        // foreach ($tweets as $key => $value) {
        //     echo $value->tweet.'<br>';
        // }

    	return view('tweets.index')
    		->with('sentiments', $sentiments)
    		->with('tweets', $tweets);
    }

    public function store(Request $request)
    {
    	$tweet = $request->input('tweet');
    	$sentiment_id = $request->input('sentiment');

    	$tweets = new Tweet;
    	$tweets->tweet = $this->replace4byte($tweet);
    	$tweets->sentiment_id = $sentiment_id;
    	$tweets->save();

    	return Redirect::to('dashboard/tweets');
    }

    public function replace4byte($string) 
    {
        return preg_replace('%(?:
              \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )%xs', '', $string);    
    }

    public function import(Request $request)
    {
        try 
        {
            $i = 0;
            Excel::load($request->file('file'), function ($reader) 
            {
                foreach ($reader->toArray() as $row) {
                    // Masukin data ke database
                    $tweets = new Tweet;
                    $tweets->tweet = $this->replace4byte($row['text']);
                    if(!empty($row['sentiment']))
                        $tweets->sentiment_id = $row['sentiment'];
                    $tweets->save();
                }


            });

            return Redirect::to('dashboard/tweets');
        } 
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }
}
