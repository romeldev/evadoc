<?php

use Illuminate\Database\Seeder;
use App\Model\Indicator;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Indicator::query()->delete();
        factory(Indicator::class, 5)->create();
    }
}
