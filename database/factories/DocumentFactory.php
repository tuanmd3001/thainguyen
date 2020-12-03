<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin\Document;
use Faker\Generator as Faker;

$factory->define(Document::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'privacy' => $faker->randomDigitNotNull,
        'status' => $faker->randomDigitNotNull,
        'thumbnail' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
