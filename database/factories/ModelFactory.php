<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(CodeProject\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(CodeProject\Entities\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'responsible' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->sentence,
        'obs' => $faker->sentence,
    ];
});

$factory->define(CodeProject\Entities\Project::class, function (Faker\Generator $faker) {
    return [
       'user_id' => $faker->numberBetween(1, 10),
       'client_id' => $faker->numberBetween(1, 10),
        'name' => $faker->word,
        'description' => $faker->sentence,
        'progress' =>  $faker->numberBetween(0,100),
        'status' => $faker->numberBetween(0,3),
        'due_date' => $faker->dateTime('now'),
    ];
});

$factory->define(CodeProject\Entities\ProjectNote::class, function (Faker\Generator $faker) {
    return [
        'project_id' => $faker->numberBetween(1, 10),
        'title' => $faker->word,
        'note' => $faker->paragraph,
    ];
});

$factory->define(CodeProject\Entities\ProjectTask::class, function (Faker\Generator $faker) {
    return [
        'project_id' => $faker->numberBetween(1, 10),
        'name' => $faker->word,
        'start_date' => $faker->dateTime('now'),
        'due_date' => $faker->dateTime('now'),
        'status' => $faker->numberBetween(0,3),
    ];
});

$factory->define(CodeProject\Entities\ProjectMember::class, function (Faker\Generator $faker) {
    return [
        'project_id' => $faker->numberBetween(1, 10),
        'user_id' => $faker->numberBetween(1, 10),
    ];
});

$factory->define(CodeProject\Entities\OAuthClient::class, function (Faker\Generator $faker) {
    return [
        'id' => 'appid01',
        'secret' => 'secret',
        'name' => 'AngularApp',
    ];
});

