<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Http\Requests;
use Redirect;

class RemoveDuplicateController extends Controller
{
    public function index()
    {
        $tweets = Tweet::all();
        return view('remove-duplicate.index')
            ->with('tweets', $tweets);
    }

    public function remove(Request $request)
    {
        $value = $request->input('value');

        $tweets = Tweet::all();
        for($i = 0; $i<count($tweets); $i++)
        {
            $str1 = $tweets[$i]->tweet;

            for($j = $i+1; $j<count($tweets); $j++)
            {
                $str2 = $tweets[$j]->tweet;

                similar_text($str1, $str2, $percent); 

                // if($percent >= $value)
                // {
                //     // echo $str1.'<br>'; 
                //     // echo $str2.'<br>';
                echo $percent.'<br><br>'; 
                //     Tweet::destroy($tweets[$j]->id);
                // }
            }
        }

        return Redirect::to('dashboard/remove-duplicate');
    }

}
