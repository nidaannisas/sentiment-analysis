<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsNrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations_nr', function (Blueprint $table) {
            $table->increments('id');
            $table->float('accuracy')->default(0);
            $table->float('precision_positive')->default(0);
            $table->float('precision_negative')->default(0);
            $table->float('precision_neutral')->default(0);
            $table->float('recall_positive')->default(0);
            $table->float('recall_negative')->default(0);
            $table->float('recall_neutral')->default(0);
            $table->float('process_time')->default(0);
            $table->string('note');
            $table->integer('positive_positive')->default(0);
            $table->integer('positive_negative')->default(0);
            $table->integer('positive_neutral')->default(0);
            $table->integer('negative_positive')->default(0);
            $table->integer('negative_negative')->default(0);
            $table->integer('negative_neutral')->default(0);
            $table->integer('neutral_positive')->default(0);
            $table->integer('neutral_negative')->default(0);
            $table->integer('neutral_neutral')->default(0);
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
