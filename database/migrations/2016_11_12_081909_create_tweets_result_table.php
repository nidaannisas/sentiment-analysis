<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets_result', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tweet');
            $table->integer('sentiment_id')->unsigned()->default(3);
            $table->integer('negated')->default(0);
            $table->enum('type', ['TEST', 'TRAIN']);
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
