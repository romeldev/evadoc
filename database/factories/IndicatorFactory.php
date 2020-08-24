<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Survey;
use Faker\Generator as Faker;
use App\Model\Indicator;
use App\Model\Evaluation;

$factory->define(Indicator::class, function (Faker $faker) {
    return [
        'order' => 1,
        'name' => $faker->unique()->words(3, true),
        'editable' => rand(0,1),
        'type_id' => rand(1, 2),
        'weight' => rand(4, 8),
        'evaluation_id' => Evaluation::inRandomOrder()->limit(1)->first()->id,
    ];
});
