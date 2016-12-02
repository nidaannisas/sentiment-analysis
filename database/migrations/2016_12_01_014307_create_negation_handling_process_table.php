<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNegationHandlingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negation_handling_process', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tweet_negated_train')->unsigned()->default(0);
            $table->integer('tweet_negated_test')->unsigned()->default(0);
            $table->integer('count_negated_positive')->unsigned()->default(0);
            $table->integer('count_negated_negative')->unsigned()->default(0);
            $table->integer('count_negated_neutral')->unsigned()->default(0);
            $table->integer('count_negated_positive_result')->unsigned()->default(0);
            $table->integer('count_negated_negative_result')->unsigned()->default(0);
            $table->integer('count_negated_positive_test')->unsigned()->default(0);
            $table->integer('count_negated_negative_test')->unsigned()->default(0);
            $table->integer('count_negated_neutral_test')->unsigned()->default(0);
            $table->integer('count_negated_positive_result_test')->unsigned()->default(0);
            $table->integer('count_negated_negative_result_test')->unsigned()->default(0);
            $table->integer('count_negated_term')->unsigned()->nullable();
            $table->integer('truncated_term_selection')->unsigned()->nullable();
            $table->float('process_time')->default(0);
            $table->boolean('evaluated')->default(false);
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
