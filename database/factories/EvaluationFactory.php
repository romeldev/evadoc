<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Model\Evaluation;
use App\Model\Survey;
use App\Model\Level;

$factory->define(Evaluation::class, function (Faker $faker) {
    $survey = Survey::inRandomOrder()->limit(1)->first();
    $level = Level::inRandomOrder()->limit(1)->first();

    return [
        'title' => 'Evaluation '.$faker->unique()->words(3, true),
        'descrip' => $faker->text(200),
        'period_id' => '1',
        'period_text' => 'SEMESTER 2020-I',
        'date_start' => '2020-03-01',
        'date_end' => '2020-07-31',
        'status' => Evaluation::STATUS_STARTED,
        'survey_id' => $survey? $survey->id: null,
        'level_id' => $level->id,
    ];
});
