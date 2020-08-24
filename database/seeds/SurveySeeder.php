<?php

use Illuminate\Database\Seeder;
use App\Model\Survey;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Survey::query()->forceDelete();
        factory(Survey::class, 2)->create();
    }
}
