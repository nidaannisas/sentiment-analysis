<?php

use Illuminate\Database\Seeder;

use App\Models\Sentiment;

class SentimentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Sentiment::create([
            'id' => 1,
            'name' => "Positive"
        ]);

        Sentiment::create([
            'id' => 2,
            'name' => "Negative"
        ]);

        Sentiment::create([
            'id' => 3,
            'name' => "Neutral"
        ]);
    }
}
