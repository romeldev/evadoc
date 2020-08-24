<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Model\Survey;
use App\Model\Scale;
use App\Model\Level;

$factory->define(Survey::class, function (Faker $faker) {
    $scale = Scale::inRandomOrder()->limit(1)->first();
    $level = Level::inRandomOrder()->limit(1)->first();
    return [
        'title' => 'Survey of '.$faker->unique()->words(2, true),
        'descrip' => $faker->text(200),
        'scale_id' => $scale->id,
        'level_id' => $level->id,
    ];
});
