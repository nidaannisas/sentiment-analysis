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
            $table->integer('count_negated_positive')->unsigned()->default(0);
            $table->integer('count_negated_negative')->unsigned()->default(0);
            $table->integer('count_negated_neutral')->unsigned()->default(0);
            $table->integer('count_negated_term')->unsigned()->nullable();
            $table->integer('count_negated_term_selection')->unsigned()->nullable();
            $table->float('process_time')->default(0);
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
