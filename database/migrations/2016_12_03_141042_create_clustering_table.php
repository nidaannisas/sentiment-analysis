<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClusteringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clustering', function (Blueprint $table) {
            $table->increments('id');
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
