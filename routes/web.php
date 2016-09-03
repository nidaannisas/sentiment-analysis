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

Route::post('dashboard/stopwords/store', [
    'as' => 'dashboard.stopwords.store',
    'uses' => 'StopwordController@store'
]);

Route::post('dashboard/stopwords/importtxt', [
    'as' => 'dashboard.stopwords.importtxt',
    'uses' => 'StopwordController@importtxt'
]);

Route::post('dashboard/stopwords/process', [
    'as' => 'dashboard.stopwords.process',
    'uses' => 'StopwordController@process'
]);

Route::get('dashboard/normalization', [
    'as' => 'dashboard.normalization.index',
    'uses' => 'NormalizationController@index'
]);

Route::post('dashboard/normalization/store', [
    'as' => 'dashboard.normalization.store',
    'uses' => 'NormalizationController@store'
]);

Route::post('dashboard/normalization/importtxt', [
    'as' => 'dashboard.normalization.importtxt',
    'uses' => 'NormalizationController@importtxt'
]);

Route::post('dashboard/normalization/process', [
    'as' => 'dashboard.normalization.process',
    'uses' => 'NormalizationController@process'
]);

Route::get('dashboard/idf', [
    'as' => 'dashboard.idf.index',
    'uses' => 'IDFController@index'
]);

Route::post('dashboard/idf/process', [
    'as' => 'dashboard.idf.process',
    'uses' => 'IDFController@process'
]);

Route::get('dashboard/naive-bayes', [
    'as' => 'dashboard.naivebayes.index',
    'uses' => 'NaiveBayesController@index'
]);

Route::post('dashboard/naive-bayes/classify', [
    'as' => 'dashboard.naivebayes.classify',
    'uses' => 'NaiveBayesController@classify'
]);