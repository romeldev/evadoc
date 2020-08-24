<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Item;
use App\Model\Survey;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'order' => 1,
        'name' => $faker->unique()->words( rand(4, 7), true),
        'value' => rand(2,7),
        'survey_id' => Survey::inRandomOrder()->limit(1)->first()->id,
    ];
});
