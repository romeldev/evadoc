<?php

use Illuminate\Database\Seeder;
use App\Model\Evaluation;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Evaluation::query()->forceDelete();
        factory(Evaluation::class)->create();
    }
}
