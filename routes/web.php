<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
});

Route::get('dashboard/tweets', [
    'as' => 'dashboard.tweets.index',
    'uses' => 'TweetController@index'
]);

Route::post('dashboard/tweets/store', [
    'as' => 'dashboard.tweets.store',
    'uses' => 'TweetController@store'
]);

Route::get('dashboard/tokenizing', [
    'as' => 'dashboard.tokenizing.index',
    'uses' => 'TokenizingController@index'
]);

Route::post('dashboard/tokenizing/tokenize', [
    'as' => 'dashboard.tokenizing.tokenize',
    'uses' => 'TokenizingController@tokenize'
]);

Route::get('dashboard/stopwords', [
    'as' => 'dashboard.stopwords.index',
    'uses' => 'StopwordController@index'
]);