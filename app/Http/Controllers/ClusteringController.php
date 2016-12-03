<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Tweet;
use App\Models\BagOfWord;
use App\Models\TweetResult;
use App\Http\Requests;
use Excel;
use DB;
use Redirect;
use Input;

class ClusteringController extends TokenizingController
{
    public function index()
    {
    	$sentiments = Sentiment::all();
    	$tweets = TweetResult::all();

    	return view('clustering.index')
    		->with('sentiments', $sentiments)
    		->with('tweets', $tweets);
    }

    public function cossine($d1, $d2, $sqrt_d1, $sqrt_d2)
    {
        $sum = 0;
        // dikaliin setiap isi trus dijumlah
        for($i = 0; $i < count($d1); $i++)
        {
            $sum += $d1[$i]*$d2[$i];
        }

        if(($sqrt_d1 * $sqrt_d2) == 0)
            return 0;
        else
            return $sum/($sqrt_d1 * $sqrt_d2);
    }

    public function process()
    {
        $start = microtime(true);
        DB::beginTransaction();

        $tweets = TweetResult::all();

        foreach($tweets as $key => $tweet)
        {
            $tweets[$key]->tweet = $this->tokenizeEvaluation($tweets[$key]->tweet);
            $tweets[$key]->tweet_unique = array_count_values($tweets[$key]->tweet);
        }

        $bows = BagOfWord::getBagOfWords();
        $bows = $this->quicksort_multidimension_object_word($bows);

        // foreach($bows as $bow)
        // {
        //     $normal = $this->BinarySearchObjectWord($bows, $bow->word, 0, count($bows)-1);
        //
        //     if($normal == -1)
        //         echo $bow->word.'<br />';
        //     //if($bow->word == "partai") echo $bow->word;
        // }

        //var_dump($bows[0]->word);
        // echo $normal = $this->BinarySearchObjectWord($bows, "partai", 0, count($bows)-1);
        //var_dump($tweets[0]->tweet_unique);
        // echo $result = isset($tweets[0]->tweet_unique["partai"]) ? $tweets[0]->tweet_unique["partai"] : 0;


        // make dtm
        $dtm = array();

        foreach($tweets as $i => $tweet)
        {
            foreach($bows as $j => $bow)
            {
                $tf = isset($tweets[$i]->tweet_unique[$bows[$j]->word]) ? $tweets[$i]->tweet_unique[$bows[$j]->word] : 0;
                $data = $this->BinarySearchObjectWord($bows, $bows[$j]->word, 0, count($bows)-1);
                $idf = 0;
                if($data != -1)
                    $idf = $bows[$data]->idf;
                $dtm[$i][$j] = $tf * $idf;
            }
        }

        $dtm_2 = array();   // dtm pangkat 2
        for($i = 0; $i < count($dtm); $i++)
        {
            for($j = 0; $j < count($dtm[$i]); $j++)
            {
                $dtm_2[$i][$j] = pow($dtm[$i][$j],2);
            }
        }

        $dtm_2_sum = array();
        for($i = 0; $i < count($dtm_2); $i++)
        {
            $sum = 0;
            for($j = 0; $j < count($dtm_2[$i]); $j++)
            {
                $sum += $dtm_2[$i][$j];
            }

            $dtm_2_sum[$i] = $sum;
        }

        $dtm_2_sqrt = array();
        for($i = 0; $i < count($dtm_2_sum); $i++)
        {
            $dtm_2_sqrt[$i] = sqrt($dtm_2_sum[$i]);
        }


        // foreach($tweets as $i => $tweet)
        // {
        //     foreach($bows as $j => $bow)
        //     {
        //         echo $dtm_2[$i][$j].' ';
        //     }
        //     echo '<br />';
        // }

        $k = 3;

        // get 3 seed random centroid
        $centroid = array();
        $key = 0;
        for($i = 0; $i < $k; $i++)
        {
            $get_key = rand(0,count($dtm)-1);

            while($get_key == $key)
                $get_key = rand(0,count($dtm)-1);

            $key = $get_key;

            $centroid[$i] = $dtm[$key];
            //echo 'centroid '.$i.' = d'.$key.'<br />';
        }

        // repeat until convergence
        $ulang = 10;

        for($ulangi = 0; $ulangi < $ulang; $ulangi++)
        {
            // sum pow 2
            $centroid_2 = array();
            for($i = 0; $i < $k; $i++)
            {
                for($j = 0; $j < count($centroid[$i]); $j++)
                {
                    $centroid_2[$i][$j] = pow($centroid[$i][$j],2);
                }
            }

            // sum centroid
            $centroid_sum = array();
            for($i = 0; $i < $k; $i++)
            {
                $sum = 0;
                for($j = 0; $j < count($centroid_2[$i]); $j++)
                {
                    $sum += $centroid_2[$i][$j];
                }

                $centroid_sum[$i] = $sum;
            }

            // sqrt centroid
            $centroid_sqrt = array();
            for($i = 0; $i < $k; $i++)
            {
                $centroid_sqrt[$i] = sqrt($centroid_sum[$i]);
            }

            $dtm_cluster = array();

            foreach($dtm as $key => $tweet)
            {
                $cossine_values = array();
                for($i = 0; $i < $k; $i++)
                {
                    $cossine_values[$i] = $this->cossine($dtm[$key], $centroid[$i], $dtm_2_sqrt[$key], $centroid_sqrt[$i]);
                }

                $highest_number = max($cossine_values);
                $index = array_search($highest_number, $cossine_values);
                $dtm_cluster[$key] = $index;

            }


            // kelompokin data sesuai cluster 0 ,1,2
            $dtm_data_cluster = array();

            foreach($dtm as $key => $tweet)
            {
                for($i = 0; $i < $k; $i++)  // cluster
                {
                    if($dtm_cluster[$key] == $i)    // jika clusternya sama
                        $dtm_data_cluster[$i][] = $dtm[$key]; // ambil data masukin ke cluster yg sama
                }
            }

            // hitung centroid baru
            foreach($dtm_data_cluster as $cluster => $tweet)
            {
                // 0
                $row_kanan = count($dtm_data_cluster[$cluster][0]); // jhumlah row ke kanan - static
                $number = count($dtm_data_cluster[$cluster]);   // jumlah per centroid
                //echo $number.'<br />';
                for($i = 0; $i < $number; $i++)
                {
                     // nol in centroid
                     for($j= 0; $j < $row_kanan; $j++)
                     {
                         $centroid[$cluster][$j] = 0;
                     }
                     for($j= 0; $j < $row_kanan; $j++)
                     {
                        $centroid[$cluster][$j] += $dtm_data_cluster[$cluster][$i][$j]; // cluster x index bawah ke i kanan ke j
                     }
                }

                for($j= 0; $j < $row_kanan; $j++)   // rata rata centroid baru
                    $centroid[$cluster][$j] = $centroid[$cluster][$j]/$number;
            }

            // for($dum = 0; $dum < count($dtm_cluster); $dum++)
            //     echo $dtm_cluster[$dum].' ';
            // echo '<br />';
        }


        $label = array(
                array(1 => 0, 2 => 0, 3 => 0),
                array(1 => 0, 2 => 0, 3 => 0),
                array(1 => 0, 2 => 0, 3 => 0)
        );
        // cari cluster positive negative neutral
        foreach($tweets as $key => $tweet)
        {
            echo $key.'<br />';
            echo $dtm_cluster[$key].'<br />';

            if($tweet->sentiment_id == 1)
            {
                var_dump($label[$dtm_cluster[$key]][1]); echo '<br />';
                $label[$dtm_cluster[$key]][1]++;
            }
            else if($tweet->sentiment_id == 2)
            {
                var_dump($label[$dtm_cluster[$key]][2]); echo '<br />';
                $label[$dtm_cluster[$key]][2]++;
            }
            else
            {
                var_dump($label[$dtm_cluster[$key]][3]); echo '<br />';
                $label[$dtm_cluster[$key]][3]++;
            }
        }

        for($i = 0; $i < $k; $i++)
        {
            $highest_number = max($label[$i]);
            $index = array_search($highest_number, $label[$i]);
            $label[$i] = $index;
        }

        $tweets = Tweet::all();
        // simpan hasil cluster
        foreach($tweets as $key => $tweet)
        {
            //echo $key.'<br />';
            $k_means = Tweet::find($tweet->id);
            //var_dump($k_means);
            $k_means->k_means = $label[$dtm_cluster[$key]];
            $k_means->save();
        }

        DB::commit();

        echo $time_elapsed_secs = microtime(true) - $start;
    }

}
