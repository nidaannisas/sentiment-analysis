<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenizingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokenizing_process', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('count_token_train')->unsigned()->default(0);
            $table->integer('count_token_test')->unsigned()->default(0);
            $table->integer('token_positive')->unsigned()->default(0);
            $table->integer('token_negative')->unsigned()->default(0);
            $table->integer('token_neutral')->unsigned()->default(0);
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
