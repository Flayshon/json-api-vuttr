<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tool;
use Faker\Generator as Faker;

$factory->define(Tool::class, function (Faker $faker) {
    return [
        'title' => $faker->company,
        'link' => $faker->url,
        'description' => $faker->text(140),
        'tags' => $faker->words(5),
    ];
});
