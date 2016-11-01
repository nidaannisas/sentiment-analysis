<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\PembagianData;
use App\Http\Requests;
use Redirect;
use DB;

class PembagianDataController extends Controller
{
    public function index()
    {
        $pembagian = PembagianData::all()->last();

        $tweets = Tweet::all();
    	$positive = Tweet::where('sentiment_id', 1)->count();
    	$negative = Tweet::where('sentiment_id', 2)->count();
    	$neutral = Tweet::where('sentiment_id', 3)->count();

    	return view('pembagian-data.index')
            ->with('pembagian', $pembagian)
            ->with('tweets', $tweets)
            ->with("positive", $positive)
            ->with("negative", $negative)
            ->with("neutral", $neutral);
    }

    public function process(Request $request)
    {
    	$p = $request->input('positive');
        $n = $request->input('negative');
        $o = $request->input('neutral');

        $positive = Tweet::getPositive();
    	$negative = Tweet::getNegative();
    	$neutral = Tweet::getNeutral();

        $positive_count = count($positive);
        $negative_count = count($negative);
        $neutral_count = count($neutral);

        DB::beginTransaction();
        $i = 0;

        $in_positive = array();
        $in_negative = array();
        $in_neutral = array();

        //random positive
        if($p > $positive_count)
        {
            return Redirect::to('dashboard/pembagian-data')
                ->with('error', 'Input positive melebihi data tweet positive');
        }
        if($n > $negative_count)
        {
            return Redirect::to('dashboard/pembagian-data')
                ->with('error', 'Input negative melebihi data tweet negative');
        }
        if($o > $neutral_count)
        {
            return Redirect::to('dashboard/pembagian-data')
                ->with('error', 'Input neutral melebihi data tweet neutral');
        }

        // change default type test
        PembagianData::defaultTestTweet();

        while($i < $p)
        {
            $r = mt_rand(0,$positive_count-1);

            if(!in_array($r, $in_positive))
            {
                $update = Tweet::find($positive[$r]->id);
                $update->type = 'TRAIN';
                $update->save();

                $in_positive[] = $r;
                $i++;
            }
        }

        // random negative
        $i = 0;
        while($i < $n)
        {
            $r = mt_rand(0,$negative_count-1);

            if(!in_array($r, $in_negative))
            {
                $update = Tweet::find($negative[$r]->id);
                $update->type = 'TRAIN';
                $update->save();

                $in_negative[] = $r;
                $i++;
            }
        }

        // random neutral
        $i = 0;
        while($i < $o)
        {
            $r = mt_rand(0,$neutral_count-1);

            if(!in_array($r, $in_neutral))
            {
                $update = Tweet::find($neutral[$r]->id);
                $update->type = 'TRAIN';
                $update->save();

                $in_neutral[] = $r;
                $i++;
            }
        }

        $pembagian = new PembagianData;
        $pembagian->positive = $p;
        $pembagian->negative = $n;
        $pembagian->neutral = $o;
        $pembagian->save();

        DB::commit();

    	return Redirect::to('dashboard/pembagian-data')
            ->with('success', 'Data telah dibagi');
    }

}
