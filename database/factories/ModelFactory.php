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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\UserArchive::class, function (Faker\Generator $faker) {
    return [
        "FirstName" => $faker->firstName,
        "LastName" => $faker->lastName,
        "HighSchool" => "Faker中学",
        "University" => "Faker大学",
        "Identity" => "HEADDEL"
    ];
});

$factory->define(App\Committee::class, function (Faker\Generator $faker) {
    return [
        'id' => 100,
        'chinese_name' => "测试委员会",
        'english_name' => "Test Committee",
        "delegation" => 1,
        "number" => $faker->numberBetween($min = 10, $max = 40),
        "topic_chinese_name" => "测试议题",
        "topic_english_name" => "Test Topic",
        "abbreviation" => "TEST",
        'language' => "English",
        'note' => $faker->text()
    ];
});

$factory->define(App\Delegation::class, function (Faker\Generator $faker) {
    $number = $faker->numberBetween($min = 4, $max = 15);
    return [
        'id' => 100,
        "head_delegate_id" => 100,
        "name" => "测试用例代表团",
        "delegate_number" => $number,
        "seat_number" => $number,
    ];
});

$factory->define(App\SeatExchange::class, function (Faker\Generator $faker) {
    return [
        "id" => 100,
        "status" => 0
    ];
});