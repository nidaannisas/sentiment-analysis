<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBagOfWordsTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bag-of-words-test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word');
            $table->float('idf')->default(0);
            $table->integer('count')->default(0);
            $table->integer('count_positive')->default(0);
            $table->integer('count_negative')->default(0);
            $table->integer('count_neutral')->default(0);
            $table->integer('count_tweet')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
