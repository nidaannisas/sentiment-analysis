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
}
