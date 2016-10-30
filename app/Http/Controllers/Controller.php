<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function BinarySearch($array, $key, $low, $high)
    {
        if( $low > $high ) // termination case
        {
            return -1;
        }

        $middle = intval( ( $low+$high )/2 ); // gets the middle of the array

        if ( $array[$middle] == $key ) // if the middle is our key
        {
            return $middle;
        }
        elseif ( $key < $array[$middle] ) // our key might be in the left sub-array
        {
            return $this->BinarySearch( $array, $key, $low, $middle-1 );
        }

        return $this->BinarySearch( $array, $key, $middle+1, $high ); // our key might be in the right sub-array
    }

    public function quicksort($seq)
    {
        if(!count($seq)) return $seq;

        $k = $seq[0];
        $x = $y = array();

        for($i=count($seq); --$i;)
        {
            if(strcmp($seq[$i], $k) <= 0)
            {
                $x[] = $seq[$i];
            }
            else
            {
                $y[] = $seq[$i];
            }
        }

        return array_merge($this->quicksort($x), array($k), $this->quicksort($y));
    }

    public function quicksort_multidimension($seq, $string)
    {
        if(!count($seq)) return $seq;

        $k = $seq[0];
        $x = $y = array();

        for($i=count($seq); --$i;)
        {
            if(strcmp($seq[$i][$string], $k[$string]) <= 0)
            {
                $x[] = $seq[$i];
            }
            else
            {
                $y[] = $seq[$i];
            }
        }

        return array_merge($this->quicksort($x), array($k), $this->quicksort($y));
    }

    public function quicksort_multidimension_object_word($seq)
    {
        if(!count($seq)) return $seq;

        $k = $seq[0];
        $x = $y = array();

        for($i=count($seq); --$i;)
        {
            if(strcmp($seq[$i]->word, $k->word) <= 0)
            {
                $x[] = $seq[$i];
            }
            else
            {
                $y[] = $seq[$i];
            }
        }

        return array_merge($this->quicksort($x), array($k), $this->quicksort($y));
    }

    function BinarySearchObjectWord($array, $key, $low, $high)
    {
        if( $low > $high ) // termination case
        {
            return -1;
        }

        $middle = intval( ( $low+$high )/2 ); // gets the middle of the array

        //echo $array[$middle]->word.'<br />';

        if ( $array[$middle]->word == $key ) // if the middle is our key
        {
            return $middle;
        }
        elseif ($key < $array[$middle]->word) // our key might be in the left sub-array
        {
            return $this->BinarySearchObjectWord( $array, $key, $low, $middle-1 );
        }

        return $this->BinarySearchObjectWord( $array, $key, $middle+1, $high ); // our key might be in the right sub-array
    }
}
