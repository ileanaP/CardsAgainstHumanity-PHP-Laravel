<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Game;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'verified' => $verified = $faker->randomElement([User::VERIFIED, User::UNVERIFIED]),
        'verification_token' => $verified == User::VERIFIED ? null : User::generateVerificationCode(),
        'password' => bcrypt('secret'), // password,
        'in_game' => null,
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Game::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'password' => bcrypt('secret'), // password,
        'creator_id' => User::inRandomOrder()->first()->id,
        'winner_id' => rand(User::inRandomOrder()->first()->id, null),
    ];
});