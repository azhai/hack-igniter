<?php
defined('BASEPATH') or exit('No direct script access allowed');

$faker = Fake_page::get_faker();


/**
 * 账户
 */
return [
    'interim' => function() use($faker) {
        return $faker->word();
    },
    'lastrun' => function() use($faker) {
        return $faker->dateTimeThisMonth()->format('Y-m-d H:i:s');
    },
];
