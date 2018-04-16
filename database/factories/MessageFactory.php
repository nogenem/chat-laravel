<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'body' => $faker->text(75),
        'from' => $faker->numberBetween(1, 10),
        'to_id' => $faker->numberBetween(1, 10),
        'to_type' => 'App\User'
    ];
});
