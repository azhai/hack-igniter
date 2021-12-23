<?php

defined('BASEPATH') || exit('No direct script access allowed');

$faker = Fake_page::get_faker();

// 管理员
return array(
    'username' => function () use ($faker) {
        return $faker->word();
    },
    'nickname' => function () use ($faker) {
        return $faker->person();
    },
    'created_at' => function () use ($faker) {
        return $faker->dateTimeThisMonth()->format('Y-m-d H:i:s');
    },
);
