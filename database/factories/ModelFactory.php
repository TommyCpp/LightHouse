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
    $id = $faker->numberBetween($min = 100, $max = 400);
    factory(App\UserArchive::class)->create([
        'id' => $id
    ]);
    return [
        'id' => $id,
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
        'id' => $faker->numberBetween($min = 100, $max = 400),
        'chinese_name' => "测试委员会",
        'english_name' => "Test Committee",
        "delegation" => 1,
        "number" => $faker->numberBetween($min = 10, $max = 40),
        "topic_chinese_name" => "测试议题",
        "topic_english_name" => "Test Topic",
        "abbreviation" => strtoupper($faker->word),
        'language' => "English",
        'note' => $faker->text()
    ];
});

$factory->define(App\Delegation::class, function (Faker\Generator $faker) {
    $number = $faker->numberBetween($min = 4, $max = 8);
    return [
        'id' => 100,
        "head_delegate_id" => 100,
        "name" => "测试用例代表团",
        "delegate_number" => $number,
        "seat_number" => $number,
    ];
});

$factory->define(App\Seat::class, function (Faker\Generator $faker) {
    return [
        'main_name' => $faker->word,
        'assist_name' => $faker->word,
        'is_distributed' => 0,
    ];
});

$factory->define(App\SeatExchange::class, function (Faker\Generator $faker) {
    return [
        "id" => 100,
        "status" => 0
    ];
});

$factory->define(App\SeatExchangeRecord::class, function (Faker\Generator $faker) {
    return [
        "request_id" => factory(App\SeatExchange::class)->create()->id
    ];
});


/*
 * 带有mock的类会在内部创建相应的关联类
 * Delegation会同步创建一个作为head_delegate的User，User的id和Delegation的id同样，是一个100~400的随机数
 * SeatExchange会同步创建两个Delegation作为initiator或者target
 */
$factory->defineAs(App\Delegation::class, 'mock', function (Faker\Generator $faker) use ($factory) {
    $delegation_id = $faker->numberBetween($min = 100, $max = 400);
    $delegation = $factory->raw(App\Delegation::class);
    $user = factory(App\User::class)->create(['id' => $delegation_id]);
    $user->archive()->save(factory(App\UserArchive::class)->create([
        "id" => $user->id
    ]));
    $user->save();
    $delegation['head_delegate_id'] = $user->id;
    $delegation['id'] = $delegation_id;
    return $delegation;
});

$factory->defineAs(App\SeatExchange::class, 'mock', function (Faker\Generator $faker) {
    return [
        "id" => 100,
        "initiator" => factory(App\Delegation::class, 'mock')->create()->id,
        "target" => factory(App\Delegation::class, 'mock')->create()->id,
    ];
});

//passed test
$factory->defineAs(App\Committee::class, 'mock', function (Faker\Generator $faker) use ($factory) {
    $committee = $factory->raw(App\Committee::class);
    $index = $faker->numberBetween($min = 1000, $max = 1200);
    for ($i = $index; $i < $index + $committee['number']; $i++) {
        factory(App\Seat::class)->create([
            "seat_id" => $i,
            "committee_id" => $committee['id'],
        ]);
    }
    return $committee;
});