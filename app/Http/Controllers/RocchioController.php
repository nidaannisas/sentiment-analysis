<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetTest;
use App\Models\BagOfWord;
use App\Models\Stopword;
use App\Models\NormalizationWord;
use App\Models\IDF;
use App\Http\Requests;

use Redirect;

class RocchioController extends Controller
{
    public function index()
    {
    	$tweets = TweetTest::orderBy('id', 'DESC')->get();

    	return view('rocchio.index')
    		->with('tweets', $tweets);
    }

    public function classify(Request $request) //request buat manggil variabe, dari html
    {
    	$tweet = $request->input('tweet');

    	$tweets = new TweetTest;
    	$tweets->tweet = $tweet; //tweet nama field di tabel tweets_tests
          $tweets->sentiment_id = $this->rocchio($tweet);
      $tweets->save();

      return Redirect::to('dashboard/rocchio');

    }

    public function tokenize($tweet)
    {
        // remove except letter
        $tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/'), array('', ' ', ''), $tweet);

        // to lower
        $tweet = strtolower($tweet);

        $words = array();
        $delim = " \n.,;-()";
        $tok = strtok($tweet, $delim); // strtok lib buat misahin berdasarkan delim yang dikasih
        // i=0;
        while ($tok !== false)
        {
            $words[] = $tok;
            $tok = strtok($delim);
            // if($words[i]==words[i-1]){
            //   jumlah++;
            // }
        }


        // unique di dalam dokumen
      //  $words = array_unique($words);


        return $words;
    }

    public function normalizeWord($tweet)
    {
        $normalizations = NormalizationWord::all();

        foreach($normalizations as $normalization)
        {
            foreach($tweet as $word)
            {
                if($word == $normalization->word)
                    $word = $normalization->normal_word;
            }
        }

        return $tweet;
    }

    public function stopwordRemoval($tweet)
    {
        $stopwords = Stopword::all();

        foreach($stopwords as $stopword)
        {
            foreach($tweet as $key => $word)
            {
                if($stopword->word == $word)
                    unset($tweet[$key]);
            }
        }

        return $tweet;
    }

    public function carisama($data){
      //$data = array("saya", "suka","suka", "kamu", "suka");
        //var_dump($data);
      $jenis[]=null;
      $cek="";
      $i=0;

      for($j=0;$j<count($data);$j++)
      {
          $index2=in_array($data[$j],$jenis); //in_array keluarannya true false
          //var_dump($data[$j]);
          //var_dump($jenis);
          //var_dump($index2);
          if($index2 == false)
          {
              $jenis[$i]=$data[$j];
              $i++;
          }

      }
      //var_dump($jenis);

      //$this->cari($data, $jenis)
      return $jenis;
    }

      function cari($data, $data2)
      {

          for($K=0;$K<count($data2);$K++)
          {
              echo $data2[$K]." => ".$this->cariyangsama($data,$data2[$K])."<br/>";
            //  $nilai= cariyangsama($data,$data2[$K])*2;
            //  echo $nilai."<br/>";

          }
      }
      function cariyangsama($data,$dupval) {
          $nb= 0;
          foreach($data as $key => $val)
          if ($val==$dupval) $nb++;
          return $nb;

      }






    public function rocchio($tweet)
    {
        // tokenize tweet
        $tweet = $this->tokenize($tweet);


        // normalize word
        $tweet = $this->normalizeWord($tweet);


        // stopword removal
        $tweet = array_values($this->stopwordRemoval($tweet));



        $count_tweets = array_count_values($tweet);

      //  var_dump($tweet);

      //  var_dump($count_tweets);

        $n = count($tweet); //ngitung jumlah word pada tweet

      //  var_dump($n);

        //  var_dump($n);
      //    $i=0;
      //  foreach ($count_tweets as $key => $value) {
       //
      //      $count_tweet[$i] = $value;
      //      echo $count_tweet[$i].'<br  />';
       //
      //      $i++;
      //  }
      $jenis = $this->carisama($tweet);


      $jum_wqwp = 0;
      $jum_wqwn = 0;
      $jum_wqwne = 0;
      $jum_bobot_q = 0;
      $jum_bobot_p = 0;
      $jum_bobot_n = 0;
      $jum_bobot_ne = 0;


               $bow = BagOfWord::all();
               $bobot_p = array();
               $bobot_n = array();
               $bobot_ne = array();
               $jum_bobot_p = 0;
               $jum_bobot_n = 0;
               $jum_bobot_ne = 0;
               foreach ($bow as $row) {
                 $idf =$row->idf;
                 $count_p = $row->count_positive;
                 $count_n = $row->count_negative;
                 $count_ne = $row->count_neutral;

                // bobot positif kuadrat
                $bobot_p[$row->id] = pow($idf * $count_p, 2);

                // bobot negatif kuadrat
                $bobot_n[$row->id] = pow($idf * $count_n, 2);

              //   bobot netral kuadrat
                $bobot_ne[$row->id] = pow($idf * $count_ne, 2);


                //jumlah semua bobot q kuadrat
              //  $jum_bobot_q = $jum_bobot_q + $bobot_q;

                //jumlah semua bobot positif kuadrat
                $jum_bobot_p = $jum_bobot_p + $bobot_p[$row->id];

                //jumlah semua bobot negatif kuadrat
                $jum_bobot_n = $jum_bobot_n + $bobot_n[$row->id];

                //jumlah semua bobot netral kuadrat
                $jum_bobot_ne = $jum_bobot_ne + $bobot_ne[$row->id];

               }

      for($K=0;$K<count($jenis);$K++)
      {
         //echo $jenis[$K]." => ".$this->cariyangsama($tweet,$jenis[$K])."<br/>";
        //  $nilai= cariyangsama($data,$data2[$K])*2;
        //  echo $nilai."<br/>";
         $term = BagOfWord::search($jenis[$K]);
         $idf= 0;
         $tf= $this->cariyangsama($tweet,$jenis[$K]);
         $count_p = 0;
         $count_n = 0;
         $count_ne = 0;





         if(!empty($term)){
           $idf=$term->idf;
           $count_p = $term->count_positive;
           $count_n = $term->count_negative;
           $count_ne = $term->count_neutral;
           }
          //bobot kueri dengan centroid
           $wqwp = $idf * $idf *$tf *$count_p;
           $wqwn = $idf * $idf *$tf *$count_n;
           $wqwne = $idf * $idf *$tf *$count_ne;


        //   echo $wqwp.' '.$wqwn.' '.$wqwne;
          //jumlah semua bobot positif
          $jum_wqwp = $jum_wqwp + $wqwp;

          //jumlah semua bobot negatif
          $jum_wqwn = $jum_wqwn + $wqwn;

          //jumlah semua bobot netral
          $jum_wqwne = $jum_wqwne + $wqwne;

        //  echo $jum_wqwp.' '.$jum_wqwn.' '.$jum_wqwne;
          // //jumlah bobot q kuadrat
          $bobot_q = pow($idf * $tf, 2);

          //bobot positif kuadrat
        //  $bobot_p = pow($idf * $count_p, 2);

          //bobot negatif kuadrat
        //  $bobot_n = pow($idf * $count_n, 2);

          //bobot netral kuadrat
        //  $bobot_ne = pow($idf * $count_ne, 2);

        //  echo $bobot_q.' '.$bobot_p.' '.$bobot_n.' '.$bobot_ne;


          //
          // //jumlah semua bobot q kuadrat
           $jum_bobot_q = $jum_bobot_q + $bobot_q;
          //
          // //jumlah semua bobot positif kuadrat
          // $jum_bobot_p = $jum_bobot_p + $bobot_p;
          //
          // //jumlah semua bobot negatif kuadrat
          // $jum_bobot_n = $jum_bobot_n + $bobot_n;
          //
          // //jumlah semua bobot netral kuadrat
          // $jum_bobot_ne = $jum_bobot_ne + $bobot_ne;
          //  echo $jum_bobot_q.' '.$jum_bobot_p.' '.$jum_bobot_n.' '.$jum_bobot_ne.'<br />';
          //echo $idf.' '.$tf.' '.$count_p.' '.$count_n.' '.$count_ne.' '.$wqwp.' '.$wqwn.' '.$wqwne.' '.$jum_wqwp.' '.$jum_wqwn.' '.$jum_wqwne.' '.$bobot_q.' '.$bobot_p.' '.$bobot_n.' '.$bobot_ne.' '.$jum_bobot_q.' '.$jum_bobot_p.' '.$jum_bobot_n.' '.$jum_bobot_ne.'<br />';




      }



      //akar
      $s_jum_bobot_q = sqrt($jum_bobot_q);
      $s_jum_bobot_p = sqrt($jum_bobot_p);
      $s_jum_bobot_n = sqrt($jum_bobot_n);
      $s_jum_bobot_ne = sqrt($jum_bobot_ne);
      //
      // echo $s_jum_bobot_q.' '.$s_jum_bobot_p.' '.$s_jum_bobot_n.' '.$s_jum_bobot_ne;
      //
      //q*positif
        $q_positif = $s_jum_bobot_q * $s_jum_bobot_p;

      //q*negatif
        $q_negatif = $s_jum_bobot_q * $s_jum_bobot_n;

      //q*netral
      $q_netral = $s_jum_bobot_q * $s_jum_bobot_ne;

    //  echo $q_positif.' '.$q_negatif.' '.$q_netral;
    //  echo $s_jum_bobot_q.' '.$s_jum_bobot_p.' '.$q_positif;
//      bobot
      if($q_positif == 0 && $q_negatif ==0 && $q_netral== 0)
        return 3;
      else{
    //  similiarity
      $sim_q_p = $jum_wqwp / $q_positif;
      $sim_q_n = $jum_wqwn / $q_negatif;
      $sim_q_ne = $jum_wqwne / $q_netral;


      if($sim_q_p > $sim_q_n && $sim_q_p > $sim_q_ne)
          return 1;
      else if($sim_q_n > $sim_q_p && $sim_q_n > $sim_q_ne)
            return 2;
      else
            return 3;
      }

         //foreach ($tweet as $word)
        // {
        //     echo $word.'<br  />';
        //
        //      $term = BagOfWord::search($word);
        //      if(!empty($term)){
        //
        //
        //      }

                // $bobot_p = $term->idf * $term->count;//bobot centroid positif dengan tweet masuk
                // $bobot_n = $term->idf * $term->count * $term->count_negative * $term->idf; //bobot centroid positif dengan tweet masuk
                // $bobot_ne = $term->idf * $term->count * $term->count_neutral * $term->idf; //bobot centroid positif dengan tweet masuk

      // }



        //$bow = BagOfWord::search()

    }
}
