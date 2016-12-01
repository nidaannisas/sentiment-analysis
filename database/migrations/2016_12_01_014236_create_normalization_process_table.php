<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNormalizationProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('normalization_process', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('count_normalization_train')->unsigned()->default(0);
            $table->integer('count_normalization_test')->unsigned()->default(0);
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
