<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagOfWord;
use App\Http\Requests;

class TokenizingController extends Controller
{
    public function index()
    {
        $words = BagOfWord::all();

        return view('tokenizing.index')
            ->with('words', $words);
    }

    public function tokenize()
    {
        $content = "This is a test string, which is used for

        demonstrating the tokenization using PHP. PHP is a very (strong) scripting-language";

        $words = array();
        $delim = " \n.,;-()";
        $tok = strtok($content, $delim);
        while ($tok !== false) {
          $words[] = $tok;
          $tok = strtok($delim);
        }
        $unique_words = array_unique($words);

        var_dump($unique_words);
    }
}
