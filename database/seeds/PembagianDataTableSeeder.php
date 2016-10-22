<?php

use Illuminate\Database\Seeder;

use App\Models\PembagianData;

class PembagianDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	PembagianData::create([
            'id' => 1,
            'positive' => 0,
            'negative' => 0,
            'neutral' => 0
        ]);

    }
}
