<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Spatie\Permission\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $action = ['create', 'edit', 'delete', 'show'][rand(0,3)];
    return [
        'name' => $faker->unique()->word.'.'.$action,
        'guard_name' => 'web',
    ];
});
