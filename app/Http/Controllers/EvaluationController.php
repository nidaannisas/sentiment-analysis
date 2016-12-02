<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\TweetResult;
use App\Models\BagOfWord;
use App\Models\TweetTest;
use App\Models\NormalizationWord;
use App\Models\Evaluation;
use App\Models\EvaluationRocchio;
use App\Models\Stopword;
use App\Models\NRRules;
use App\Models\EvaluationNR;
use App\Models\EvaluationBernoulli;
use App\Models\TokenizingProcess;
use App\Models\NormalizationProcess;
use App\Models\StopwordProcess;
use App\Models\NegationHandlingProcess;
use App\Models\FeatureSelection;
use App\Models\PembagianData;
use App\Http\Requests;
use Redirect;
use DB;

class EvaluationController extends NaiveBayesController
{
    public function index()
    {
    	$evaluations = Evaluation::orderBy('id', 'DESC')->get();

    	return view('evaluation.index')
    		->with('evaluations', $evaluations);
    }

    public function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }

        return $temp_array;
    }

    // public function tokenizing($tweets)
    // {
    //     $words = array();
    //     $count_positive = 0;
    //     $count_negative = 0;
    //     $count_neutral = 0;
    //
    //     $count_tweet_positive = 0;
    //     $count_tweet_negative = 0;
    //     $count_tweet_neutral = 0;
    //
    //     $i = 0;
    //     foreach($tweets as $tweet)
    //     {
    //         if($tweet->sentiment_id == 1)
    //             $count_tweet_positive++;
    //         else if($tweet->sentiment_id == 2)
    //             $count_tweet_negative++;
    //         else
    //             $count_tweet_neutral++;
    //
    //         // remove except letter
    //         //$tweet->tweet = preg_replace('#^https?://*/', '', $tweet->tweet);
    //         $tweet->tweet = preg_replace(array('/[^a-zA-Z_ -]/', '/[ -]+/', '/^-|-$/', '#^https?([a-zA-Z_ -]*)#'), array('', ' ', ''), $tweet->tweet);
    //
    //         // to lower
    //         $tweet->tweet = strtolower($tweet->tweet);
    //
    //         $delim = " \n.,;-()";
    //         $tok = strtok($tweet->tweet, $delim);
    //
    //         while ($tok !== false)
    //         {
    //             $words[$i]['term'] = $tok;
    //             $tok = strtok($delim);
    //
    //             if($tweet->sentiment_id == 1)
    //             {
    //                 $words[$i]['count_positive'] = $count_positive + 1;
    //             }
    //             else if($tweet->sentiment_id == 2)
    //             {
    //                 $words[$i]['count_negative'] = $count_negative + 1;
    //             }
    //             else
    //             {
    //                 $words[$i]['count_neutral'] = $count_neutral + 1;
    //             }
    //
    //             $i++;
    //         }
    //
    //
    //     }
    //
    //     $words = $this->quicksort_multidimension($words, 'term');
    //
    //
    //     // $words = array_values($words);
    //
    //     $result = array("words" => $words,
    //                     "count_positive" => $count_positive,
    //                     "count_negative" => $count_negative,
    //                     "count_neutral" => $count_neutral,
    //                     "count_tweet_positive" => $count_tweet_positive = 0,
    //                     "count_tweet_negative" => $count_tweet_negative = 0,
    //                     "count_tweet_neutral" => $count_tweet_neutral = 0,
    //                 );
    //
    //     return $result;
    // }
    //
    // public function normalization($tweets)
    // {
    //     $normalizations = NormalizationWord::all();
    //
    //     foreach($normalizations as $normalization)
    //     {
    //         $search = $this->BinarySearch($tweets, $normalization->word, 0, count($tweets)-1);
    //         if($search > -1)
    //         {
    //             $tweets[$search] = $normalization->normal_word;
    //         }
    //
    //     }
    //
    //     return $tweets;
    // }
    //
    // public function stopword($tweets)
    // {
    //     $stopwords = Stopword::all();
    //
    //     foreach($stopwords as $stopword)
    //     {
    //         $search = $this->BinarySearch($tweets, $stopword->word, 0, count($tweets)-1);
    //         if($search > -1)
    //         {
    //             unset($tweets[$search]);
    //             $tweets = array_values($tweets);
    //         }
    //     }
    //
    //     return $tweets;
    // }
    //
    // public function naiveBayes($data)
    // {
    //     $p_positive = $count_tweet_positive/$N;
    //     $p_negative = $count_tweet_negative/$N;
    //     $p_neutral = $count_tweet_neutral/$N;
    //
    //     // size vocabulary
    //     $v = count($words);
    //
    //     foreach($test as $tweet)
    //     {
    //         // calculate positive
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_positive + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_positive = $p_positive * $p_word;
    //         }
    //
    //         // calculate negative
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_negative + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_negative = $p_negative * $p_word;
    //         }
    //
    //         // calculate neutral
    //         foreach($tweet as $word)
    //         {
    //             $p_word = ($count_ + 1)/(BagOfWord::countWord($word) + $v);
    //             $p_neutral = $p_neutral * $p_word;
    //         }
    //
    //         if($p_positive > $p_negative && $p_positive > $p_neutral)
    //             return 1;   // positive
    //         else if($p_negative > $p_positive && $p_negative > $p_neutral)
    //             return 2;   // negative
    //         else
    //             return 3;   // neutral
    //     }
    // }

    public function evaluateKFold()
    {
        $start = microtime(true);

        $tweets = Tweet::getTweets();
        $train = array_slice($tweets,0,13);
        $test = array_slice($tweets,8,10);
        $N = count($train);

        // tokenizing
        $tokenizing = $this->tokenizing($tweets);

        //foreach($tokenizing['words'] as $t) echo $t['term'].'<br />';
        // $words = $tokenizing['words'];
        //
        // $count_positive = $tokenizing['count_positive'];
        // $count_negative = $tokenizing['count_negative'];
        // $count_neutral = $tokenizing['count_neutral'];
        //
        // $count_tweet_positive = $tokenizing['count_tweet_positive'];
        // $count_tweet_negative = $tokenizing['count_tweet_negative'];
        // $count_tweet_neutral = $tokenizing['count_tweet_neutral'];
        //
        // // sort bag of words
        // $words = $this->quicksort($words);
        //
        // // words normalization
        // $words = $this->normalization($words);
        //
        // // stopwords
        // $words = $this->stopword($words);
        //
        // // Seleksi fitur gimana ?
        //
        // // naive bayes


        $time_elapsed_secs = microtime(true) - $start;

        echo ' '.$time_elapsed_secs;
    }

    public function evaluate(Request $request)
    {
        $data = $request->input('data');
        $start = microtime(true);

        $use_feature_selection = $request->input('feature_selection');
        $use_negation_handling = $request->input('negation_handling');

        DB::beginTransaction();

        if($data == 'TRAIN')
            $tweets = TweetResult::getTrain();
        else if($data == 'TEST')
            $tweets = TweetResult::getTest();
        else
            $tweets = TweetResult::getTweets();

        $count_default_class_positive = 0;
        $count_default_class_negative = 0;
        $count_default_class_neutral = 0;

        $count_class_positive = 0;
        $count_class_negative = 0;
        $count_class_neutral = 0;

        $right_class = 0;
        $right_class_positive = 0;  // hasil positif dan tweet positif
        $right_class_negative = 0;
        $right_class_neutral = 0;

        // confusion matrix
        $positive_negative = 0; // hasil positif tapi tweet negatif
        $positive_neutral = 0;
        $negative_positive = 0;
        $negative_neutral = 0;
        $neutral_positive = 0;
        $neutral_negative = 0;

        $N = count($tweets);

        $data = (object) array('N' => count(TweetResult::getTrain()),
                                'countPositiveTrain' => TweetResult::countPositiveTrain(),
                                'countNegativeTrain' => TweetResult::countNegativeTrain(),
                                'countNeutralTrain' => TweetResult::countNeutralTrain(),
                                'v' => count(BagOfWord::all()),
                                'countWordPositive' => BagOfWord::countWordPositive(),
                                'countWordNegative' => BagOfWord::countWordNegative(),
                                'countWordNeutral' => BagOfWord::countWordNeutral()
                            );

        foreach($tweets as $tweet)
        {
            $class = $this->naiveBayesEvaluate($tweet->tweet, $data);

            if($tweet->sentiment_id == 1)
                $count_default_class_positive++;
            else if($tweet->sentiment_id == 2)
                $count_default_class_negative++;
            else
                $count_default_class_neutral++;

            if($class == 1)
                $count_class_positive++;
            else if($class == 2)
                $count_class_negative++;
            else
                $count_class_neutral++;

            if($class == $tweet->sentiment_id)
            {
                $right_class++;

                if($class == 1)
                    $right_class_positive++;
                else if($class == 2)
                    $right_class_negative++;
                else
                    $right_class_neutral++;
            }

            if($class == 1 && $tweet->sentiment_id == 2)
                $positive_negative++;
            else if($class == 1 && $tweet->sentiment_id == 3)
                $positive_neutral++;
            else if($class == 2 && $tweet->sentiment_id == 1)
                $negative_positive++;
            else if($class == 2 && $tweet->sentiment_id == 3)
                $negative_neutral++;
            else if($class == 3 && $tweet->sentiment_id == 1)
                $neutral_positive++;
            else if($class == 3 && $tweet->sentiment_id == 2)
                $neutral_negative++;
        }

        $accuracy = ($right_class/$N)*100;
        $precision_positive = ($right_class_positive/$count_class_positive)*100;
        $precision_negative = ($right_class_negative/$count_class_negative)*100;
        $precision_neutral = ($right_class_neutral/$count_class_neutral)*100;

        $recall_positive = ($right_class_positive/$count_default_class_positive)*100;
        $recall_negative = ($right_class_negative/$count_default_class_negative)*100;
        $recall_neutral = ($right_class_neutral/$count_default_class_neutral)*100;

        $time_elapsed_secs = microtime(true) - $start;

        $evaluation = new Evaluation;
        $evaluation->accuracy = $accuracy;
        $evaluation->precision_positive = $precision_positive;
        $evaluation->precision_negative = $precision_negative;
        $evaluation->precision_neutral = $precision_neutral;
        $evaluation->recall_positive = $recall_positive;
        $evaluation->recall_negative = $recall_negative;
        $evaluation->recall_neutral = $recall_neutral;
        $evaluation->note = $request->input('note');
        $evaluation->process_time = $time_elapsed_secs;

        // confusion matrix
        $evaluation->positive_positive = $right_class_positive;
        $evaluation->positive_negative = $positive_negative;
        $evaluation->positive_neutral = $positive_neutral;
        $evaluation->negative_negative = $right_class_negative;
        $evaluation->negative_positive = $negative_positive;
        $evaluation->negative_neutral = $negative_neutral;
        $evaluation->neutral_neutral = $right_class_neutral;
        $evaluation->neutral_positive = $neutral_positive;
        $evaluation->neutral_negative = $neutral_negative;

        // data process
        $evaluation->pembagian_data_id = PembagianData::get()->id;
        $evaluation->tokenizing_process_id = TokenizingProcess::get()->id;
        $evaluation->normalization_process_id = NormalizationProcess::get()->id;
        $evaluation->stopword_process_id = StopwordProcess::get()->id;
        if($use_negation_handling)
        {
            $evaluation->negation_handling_process_id = NegationHandlingProcess::get()->id;
            // negation handling evaluated true
            $negation = NegationHandlingProcess::get();
            var_dump($negation);
            $negation_evaluate = NegationHandlingProcess::find($negation->id);
            var_dump($negation_evaluate);
            $negation_evaluate->evaluated = true;
            $negation_evaluate->save();
        }

        if($use_feature_selection)
            $evaluation->feature_selection_id = FeatureSelection::get()->id;

        $evaluation->save();

        DB::commit();

        return Redirect::to('dashboard/evaluation');
    }

    public function indexRocchio()
    {
        $evaluations = EvaluationRocchio::orderBy('id', 'DESC')->get();

    	return view('evaluation.rocchio')
    		->with('evaluations', $evaluations);
    }

    public function evaluateRocchio(Request $request)
    {
        $data = $request->input('data');
        $start = microtime(true);

        $use_feature_selection = $request->input('feature_selection');
        $use_negation_handling = $request->input('negation_handling');

        DB::beginTransaction();

        if($data == 'TRAIN')
            $tweets = TweetResult::getTrain();
        else if($data == 'TEST')
            $tweets = TweetResult::getTest();
        else
            $tweets = TweetResult::getTweets();

        $count_default_class_positive = 0;
        $count_default_class_negative = 0;
        $count_default_class_neutral = 0;

        $count_class_positive = 0;
        $count_class_negative = 0;
        $count_class_neutral = 0;

        $right_class = 0;
        $right_class_positive = 0;  // hasil positif dan tweet positif
        $right_class_negative = 0;
        $right_class_neutral = 0;

        // confusion matrix
        $positive_negative = 0; // hasil positif tapi tweet negatif
        $positive_neutral = 0;
        $negative_positive = 0;
        $negative_neutral = 0;
        $neutral_positive = 0;
        $neutral_negative = 0;

        $N = count($tweets);


        foreach($tweets as $tweet)
        {
            $class = $this->rocchio($tweet->tweet);

            if($tweet->sentiment_id == 1)
                $count_default_class_positive++;
            else if($tweet->sentiment_id == 2)
                $count_default_class_negative++;
            else
                $count_default_class_neutral++;

            if($class == 1)
                $count_class_positive++;
            else if($class == 2)
                $count_class_negative++;
            else
                $count_class_neutral++;

            if($class == $tweet->sentiment_id)
            {
                $right_class++;

                if($class == 1)
                    $right_class_positive++;
                else if($class == 2)
                    $right_class_negative++;
                else
                    $right_class_neutral++;
            }

            if($class == 1 && $tweet->sentiment_id == 2)
                $positive_negative++;
            else if($class == 1 && $tweet->sentiment_id == 3)
                $positive_neutral++;
            else if($class == 2 && $tweet->sentiment_id == 1)
                $negative_positive++;
            else if($class == 2 && $tweet->sentiment_id == 3)
                $negative_neutral++;
            else if($class == 3 && $tweet->sentiment_id == 1)
                $neutral_positive++;
            else if($class == 3 && $tweet->sentiment_id == 2)
                $neutral_negative++;
        }

        // echo $right_class.' '.$N.'<br />';
        // echo $right_class_positive.' '.$count_class_positive.'<br />';
        // echo $right_class_negative.' '.$count_class_negative.'<br />';
        // echo $right_class_neutral.' '.$count_class_neutral.'<br />';

        $accuracy = ($right_class/$N)*100;
        $precision_positive = ($right_class_positive/$count_class_positive)*100;
        $precision_negative = ($right_class_negative/$count_class_negative)*100;
        $precision_neutral = ($right_class_neutral/$count_class_neutral)*100;

        $recall_positive = ($right_class_positive/$count_default_class_positive)*100;
        $recall_negative = ($right_class_negative/$count_default_class_negative)*100;
        $recall_neutral = ($right_class_neutral/$count_default_class_neutral)*100;

        $time_elapsed_secs = microtime(true) - $start;

        $evaluation = new EvaluationRocchio;
        $evaluation->accuracy = $accuracy;
        $evaluation->precision_positive = $precision_positive;
        $evaluation->precision_negative = $precision_negative;
        $evaluation->precision_neutral = $precision_neutral;
        $evaluation->recall_positive = $recall_positive;
        $evaluation->recall_negative = $recall_negative;
        $evaluation->recall_neutral = $recall_neutral;
        $evaluation->note = $request->input('note');
        $evaluation->process_time = $time_elapsed_secs;

        // confusion matrix
        $evaluation->positive_positive = $right_class_positive;
        $evaluation->positive_negative = $positive_negative;
        $evaluation->positive_neutral = $positive_neutral;
        $evaluation->negative_negative = $right_class_negative;
        $evaluation->negative_positive = $negative_positive;
        $evaluation->negative_neutral = $negative_neutral;
        $evaluation->neutral_neutral = $right_class_neutral;
        $evaluation->neutral_positive = $neutral_positive;
        $evaluation->neutral_negative = $neutral_negative;

        // data process
        $evaluation->pembagian_data_id = PembagianData::get()->id;
        $evaluation->tokenizing_process_id = TokenizingProcess::get()->id;
        $evaluation->normalization_process_id = NormalizationProcess::get()->id;
        $evaluation->stopword_process_id = StopwordProcess::get()->id;
        if($use_negation_handling)
        {
            $evaluation->negation_handling_process_id = NegationHandlingProcess::get()->id;
            // negation handling evaluated true
            $negation = NegationHandlingProcess::get();
            $negation_evaluate = NegationHandlingProcess::find($negation->id);
            $negation_evaluate->evaluated = true;
            $negation_evaluate->save();
        }

        if($use_feature_selection)
            $evaluation->feature_selection_id = FeatureSelection::get()->id;

        $evaluation->save();

        DB::commit();

        return Redirect::to('dashboard/evaluation-rocchio');
    }

    public function rocchio($tweet)
    {
        // tokenize tweet
        $tweet = $this->tokenizeEvaluation($tweet);

        // unikin tweet masuk dulu, biar bisa dihitung tf tweet masuknya
        $tf_tweet = array_count_values($tweet);
        $tweet = array_unique($tweet);

        $sum_centroid_positive = 0;
        $sum_centroid_negative = 0;
        $sum_centroid_neutral = 0;
        $sum_q_2 = 0;
        $sum_positive_2 = 0;
        $sum_negative_2 = 0;
        $sum_neutral_2 = 0;

        // hitung centroid
        foreach($tweet as $key => $word)
        {
            $bow = BagOfWord::search($word);

            // tf q*IDF
            if(empty($bow))
                $tfidf = 0;
            else
                $tfidf = $tf_tweet[$word] * $bow->idf;
            $tfidf_2 = $tfidf * $tfidf;
            $sum_q_2 += $tfidf_2;

            // centroid positive
            // tfidf word positive
            if(empty($bow))
                $tfidf_word_positive = 0;
            else
                $tfidf_word_positive = $bow->count_positive * $bow->idf;

            $tfidf_word_positive_2 = $tfidf_word_positive * $tfidf_word_positive;
            $sum_positive_2 += $tfidf_word_positive_2;

            $centroid_positive = $tfidf * $tfidf_word_positive;
            $sum_centroid_positive += $centroid_positive;

            // tfidf word negative
            if(empty($bow))
                $tfidf_word_negative = 0;
            else
                $tfidf_word_negative = $bow->count_negative * $bow->idf;

            $tfidf_word_negative_2 = $tfidf_word_negative * $tfidf_word_negative;
            $sum_negative_2 += $tfidf_word_negative_2;

            $centroid_negative = $tfidf * $tfidf_word_negative;
            $sum_centroid_negative += $centroid_negative;

            // tfidf word neutral
            if(empty($bow))
                $tfidf_word_neutral = 0;
            else
                $tfidf_word_neutral = $bow->count_neutral * $bow->idf;

            $tfidf_word_neutral_2 = $tfidf_word_neutral * $tfidf_word_neutral;
            $sum_neutral_2 += $tfidf_word_neutral_2;

            $centroid_neutral = $tfidf * $tfidf_word_neutral;
            $sum_centroid_neutral += $centroid_neutral;
        }

        $sum_q_2_sqrt = sqrt($sum_q_2);
        $sum_positive_2_sqrt = sqrt($sum_positive_2);
        $sum_negative_2_sqrt = sqrt($sum_negative_2);
        $sum_neutral_2_sqrt = sqrt($sum_neutral_2);

        $q_positive = $sum_q_2_sqrt * $sum_positive_2_sqrt;
        $q_negative = $sum_q_2_sqrt * $sum_negative_2_sqrt;
        $q_neutral = $sum_q_2_sqrt * $sum_neutral_2_sqrt;

        $p_positive = 0;
        $p_negative = 0;
        $p_neutral = 0;

        if($q_positive != 0)
            $p_positive = $sum_centroid_positive / $q_positive;
        if($q_negative != 0)
            $p_negative = $sum_centroid_negative / $q_negative;
        if($q_neutral != 0)
            $p_neutral = $sum_centroid_neutral / $q_neutral;

        $values = array($p_positive, $p_negative, $p_neutral);
        $highest_number = max($values);
        $key = array_search($highest_number, $values);

        return $key+1;
    }

    public function indexNR()
    {
        $evaluations = EvaluationNR::orderBy('id', 'DESC')->get();

    	return view('evaluation.nr')
    		->with('evaluations', $evaluations);
    }

    public function evaluateNR(Request $request)
    {
        $data = $request->input('data');
        $start = microtime(true);

        $use_feature_selection = $request->input('feature_selection');
        $use_negation_handling = $request->input('negation_handling');

        DB::beginTransaction();

        if($data == 'TRAIN')
            $tweets = TweetResult::getTrain();
        else if($data == 'TEST')
            $tweets = TweetResult::getTest();
        else
            $tweets = TweetResult::getTweets();

        $count_default_class_positive = 0;
        $count_default_class_negative = 0;
        $count_default_class_neutral = 0;

        $count_class_positive = 0;
        $count_class_negative = 0;
        $count_class_neutral = 0;

        $right_class = 0;
        $right_class_positive = 0;  // hasil positif dan tweet positif
        $right_class_negative = 0;
        $right_class_neutral = 0;

        // confusion matrix
        $positive_negative = 0; // hasil positif tapi tweet negatif
        $positive_neutral = 0;
        $negative_positive = 0;
        $negative_neutral = 0;
        $neutral_positive = 0;
        $neutral_negative = 0;

        $N = count($tweets);

        $data = (object) array('N' => count(TweetResult::getTrain()),
                                'countPositiveTrain' => TweetResult::countPositiveTrain(),
                                'countNegativeTrain' => TweetResult::countNegativeTrain(),
                                'countNeutralTrain' => TweetResult::countNeutralTrain(),
                                'v' => count(BagOfWord::all()),
                                'countWordPositive' => BagOfWord::countWordPositive(),
                                'countWordNegative' => BagOfWord::countWordNegative(),
                                'countWordNeutral' => BagOfWord::countWordNeutral()
                            );

        $rules = NRRules::all();

        foreach($tweets as $tweet)
        {
            $class = $this->naiveBayesEvaluate($tweet->tweet, $data);
            $class_rocchio = $this->rocchio($tweet->tweet);

            if($class != $class_rocchio)
            {
                foreach($rules as $rule)
                {
                    if($rule->naive_bayes == $class && $rule->rocchio == $class_rocchio)
                        $class = $rule->result;
                }
            }

            if($tweet->sentiment_id == 1)
                $count_default_class_positive++;
            else if($tweet->sentiment_id == 2)
                $count_default_class_negative++;
            else
                $count_default_class_neutral++;

            if($class == 1)
                $count_class_positive++;
            else if($class == 2)
                $count_class_negative++;
            else
                $count_class_neutral++;

            if($class == $tweet->sentiment_id)
            {
                $right_class++;

                if($class == 1)
                    $right_class_positive++;
                else if($class == 2)
                    $right_class_negative++;
                else
                    $right_class_neutral++;
            }

            if($class == 1 && $tweet->sentiment_id == 2)
                $positive_negative++;
            else if($class == 1 && $tweet->sentiment_id == 3)
                $positive_neutral++;
            else if($class == 2 && $tweet->sentiment_id == 1)
                $negative_positive++;
            else if($class == 2 && $tweet->sentiment_id == 3)
                $negative_neutral++;
            else if($class == 3 && $tweet->sentiment_id == 1)
                $neutral_positive++;
            else if($class == 3 && $tweet->sentiment_id == 2)
                $neutral_negative++;
        }

        $accuracy = ($right_class/$N)*100;
        $precision_positive = ($right_class_positive/$count_class_positive)*100;
        $precision_negative = ($right_class_negative/$count_class_negative)*100;
        $precision_neutral = ($right_class_neutral/$count_class_neutral)*100;

        $recall_positive = ($right_class_positive/$count_default_class_positive)*100;
        $recall_negative = ($right_class_negative/$count_default_class_negative)*100;
        $recall_neutral = ($right_class_neutral/$count_default_class_neutral)*100;

        $time_elapsed_secs = microtime(true) - $start;

        $evaluation = new EvaluationNR;
        $evaluation->accuracy = $accuracy;
        $evaluation->precision_positive = $precision_positive;
        $evaluation->precision_negative = $precision_negative;
        $evaluation->precision_neutral = $precision_neutral;
        $evaluation->recall_positive = $recall_positive;
        $evaluation->recall_negative = $recall_negative;
        $evaluation->recall_neutral = $recall_neutral;
        $evaluation->note = $request->input('note');
        $evaluation->process_time = $time_elapsed_secs;
        // confusion matrix
        $evaluation->positive_positive = $right_class_positive;
        $evaluation->positive_negative = $positive_negative;
        $evaluation->positive_neutral = $positive_neutral;
        $evaluation->negative_negative = $right_class_negative;
        $evaluation->negative_positive = $negative_positive;
        $evaluation->negative_neutral = $negative_neutral;
        $evaluation->neutral_neutral = $right_class_neutral;
        $evaluation->neutral_positive = $neutral_positive;
        $evaluation->neutral_negative = $neutral_negative;

        // data process
        $evaluation->pembagian_data_id = PembagianData::get()->id;
        $evaluation->tokenizing_process_id = TokenizingProcess::get()->id;
        $evaluation->normalization_process_id = NormalizationProcess::get()->id;
        $evaluation->stopword_process_id = StopwordProcess::get()->id;
        if($use_negation_handling)
        {
            $evaluation->negation_handling_process_id = NegationHandlingProcess::get()->id;
            // negation handling evaluated true
            $negation = NegationHandlingProcess::get();
            $negation_evaluate = NegationHandlingProcess::find($negation->id);
            $negation_evaluate->evaluated = true;
            $negation_evaluate->save();
        }

        if($use_feature_selection)
            $evaluation->feature_selection_id = FeatureSelection::get()->id;

        $evaluation->save();

        DB::commit();

        return Redirect::to('dashboard/evaluation-nr');
    }

    public function indexBernoulli()
    {
        $evaluations = EvaluationBernoulli::orderBy('id', 'DESC')->get();

    	return view('evaluation.bernoulli')
    		->with('evaluations', $evaluations);
    }

    public function bernoulli($tweet, $data)
    {
        // tokenize tweet
        $tweet = $this->tokenizeEvaluation($tweet);

        // jumlah dokumen
        $N = $data->N;

        $p_positive = $data->countPositiveTrain/$N;
        $p_negative = $data->countNegativeTrain/$N;
        $p_neutral = $data->countNeutralTrain/$N;

        // size vocabulary
        $v = $data->v;

        // calculate positive
        foreach($tweet as $word)
        {
            $df = 0;
            $bags = BagOfWord::search($word);
            if(!empty($bags))
            {
                $df = $bags->count_tweet;
            }
            $p_word = ($df + 1)/($data->countPositiveTrain + 3);
            $p_positive = $p_positive * $p_word;

            echo $p_positive.'-'.$p_word.'-'.$df.' ';
        }

        echo '<br />==============================================================<br />';

        // calculate negative
        foreach($tweet as $word)
        {
            $df = 0;
            $bags = BagOfWord::search($word);
            if(!empty($bags))
            {
                $df = $bags->count_tweet;
            }
            $p_word = ($df + 1)/($data->countNegativeTrain + 3);
            $p_negative = $p_negative * $p_word;

            echo $p_negative.'-'.$p_word.'-'.$df.' ';
        }

        echo '<br />==============================================================<br />';
        // calculate neutral
        foreach($tweet as $word)
        {
            $df = 0;
            $bags = BagOfWord::search($word);
            if(!empty($bags))
            {
                $df = $bags->count_tweet;
            }
            $p_word = ($df + 1)/($data->countNeutralTrain + 3);
            $p_neutral = $p_neutral * $p_word;

            echo $p_neutral.'-'.$p_word.'-'.$df.' ';
        }
        echo '<br />==============================================================<br />';

        echo $p_positive.' '.$p_negative.' '.$p_neutral.'<br />';

        $values = array($p_positive, $p_negative, $p_neutral);
        $highest_number = max($values);
        $key = array_search($highest_number, $values);

        return $key+1;
    }

    public function evaluateBernoulli(Request $request)
    {
        $data = $request->input('data');
        $start = microtime(true);

        $use_feature_selection = $request->input('feature_selection');
        $use_negation_handling = $request->input('negation_handling');

        DB::beginTransaction();

        if($data == 'TRAIN')
            $tweets = TweetResult::getTrain();
        else if($data == 'TEST')
            $tweets = TweetResult::getTest();
        else
            $tweets = TweetResult::getTweets();

        $count_default_class_positive = 0;
        $count_default_class_negative = 0;
        $count_default_class_neutral = 0;

        $count_class_positive = 0;
        $count_class_negative = 0;
        $count_class_neutral = 0;

        $right_class = 0;
        $right_class_positive = 0;  // hasil positif dan tweet positif
        $right_class_negative = 0;
        $right_class_neutral = 0;

        // confusion matrix
        $positive_negative = 0; // hasil positif tapi tweet negatif
        $positive_neutral = 0;
        $negative_positive = 0;
        $negative_neutral = 0;
        $neutral_positive = 0;
        $neutral_negative = 0;

        $N = count($tweets);

        $data = (object) array('N' => count(TweetResult::getTrain()),
                                'countPositiveTrain' => TweetResult::countPositiveTrain(),
                                'countNegativeTrain' => TweetResult::countNegativeTrain(),
                                'countNeutralTrain' => TweetResult::countNeutralTrain(),
                                'v' => count(BagOfWord::all()),
                                'countWordPositive' => BagOfWord::countWordPositive(),
                                'countWordNegative' => BagOfWord::countWordNegative(),
                                'countWordNeutral' => BagOfWord::countWordNeutral()
                            );

        foreach($tweets as $tweet)
        {
            $class = $this->bernoulli($tweet->tweet, $data);

            if($tweet->sentiment_id == 1)
                $count_default_class_positive++;
            else if($tweet->sentiment_id == 2)
                $count_default_class_negative++;
            else
                $count_default_class_neutral++;

            if($class == 1)
                $count_class_positive++;
            else if($class == 2)
                $count_class_negative++;
            else
                $count_class_neutral++;

            if($class == $tweet->sentiment_id)
            {
                $right_class++;

                if($class == 1)
                    $right_class_positive++;
                else if($class == 2)
                    $right_class_negative++;
                else
                    $right_class_neutral++;
            }

            if($class == 1 && $tweet->sentiment_id == 2)
                $positive_negative++;
            else if($class == 1 && $tweet->sentiment_id == 3)
                $positive_neutral++;
            else if($class == 2 && $tweet->sentiment_id == 1)
                $negative_positive++;
            else if($class == 2 && $tweet->sentiment_id == 3)
                $negative_neutral++;
            else if($class == 3 && $tweet->sentiment_id == 1)
                $neutral_positive++;
            else if($class == 3 && $tweet->sentiment_id == 2)
                $neutral_negative++;
        }

        //echo $right_class.' '.$N.' '.$right_class_positive.' '.$count_class_positive;

        $precision_positive = 0;
        $precision_negative = 0;
        $precision_neutral = 0;
        $recall_positive = 0;
        $recall_negative = 0;
        $recall_neutral = 0;

        $accuracy = ($right_class/$N)*100;

        if($count_class_positive != 0)
            $precision_positive = ($right_class_positive/$count_class_positive)*100;

        if($count_class_negative != 0)
            $precision_negative = ($right_class_negative/$count_class_negative)*100;
        if($count_class_neutral != 0)
            $precision_neutral = ($right_class_neutral/$count_class_neutral)*100;

        if($count_default_class_positive != 0)
            $recall_positive = ($right_class_positive/$count_default_class_positive)*100;
        if($count_default_class_negative != 0)
            $recall_negative = ($right_class_negative/$count_default_class_negative)*100;
        if($count_default_class_neutral != 0)
            $recall_neutral = ($right_class_neutral/$count_default_class_neutral)*100;

        $time_elapsed_secs = microtime(true) - $start;

        $evaluation = new EvaluationBernoulli;
        $evaluation->accuracy = $accuracy;
        $evaluation->precision_positive = $precision_positive;
        $evaluation->precision_negative = $precision_negative;
        $evaluation->precision_neutral = $precision_neutral;
        $evaluation->recall_positive = $recall_positive;
        $evaluation->recall_negative = $recall_negative;
        $evaluation->recall_neutral = $recall_neutral;
        $evaluation->note = $request->input('note');
        $evaluation->process_time = $time_elapsed_secs;
        // confusion matrix
        $evaluation->positive_positive = $right_class_positive;
        $evaluation->positive_negative = $positive_negative;
        $evaluation->positive_neutral = $positive_neutral;
        $evaluation->negative_negative = $right_class_negative;
        $evaluation->negative_positive = $negative_positive;
        $evaluation->negative_neutral = $negative_neutral;
        $evaluation->neutral_neutral = $right_class_neutral;
        $evaluation->neutral_positive = $neutral_positive;
        $evaluation->neutral_negative = $neutral_negative;

        // data process
        $evaluation->pembagian_data_id = PembagianData::get()->id;
        $evaluation->tokenizing_process_id = TokenizingProcess::get()->id;
        $evaluation->normalization_process_id = NormalizationProcess::get()->id;
        $evaluation->stopword_process_id = StopwordProcess::get()->id;
        if($use_negation_handling)
        {
            $evaluation->negation_handling_process_id = NegationHandlingProcess::get()->id;
            // negation handling evaluated true
            $negation = NegationHandlingProcess::get();
            $negation_evaluate = NegationHandlingProcess::find($negation->id);
            $negation_evaluate->evaluated = true;
            $negation_evaluate->save();
        }

        if($use_feature_selection)
            $evaluation->feature_selection_id = FeatureSelection::get()->id;

        $evaluation->save();

        DB::commit();

        //return Redirect::to('dashboard/evaluation-bernoulli');
    }
}
