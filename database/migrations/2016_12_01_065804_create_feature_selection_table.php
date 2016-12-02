<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureSelectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_selection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tf')->unsigned()->default(0);
            $table->integer('truncated_term_tf')->unsigned()->default(0);
            $table->integer('df')->unsigned()->default(0);
            $table->integer('truncated_term_df')->unsigned()->default(0);
            $table->float('idf')->unsigned()->default(0);
            $table->integer('truncated_term_idf')->unsigned()->default(0);
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
