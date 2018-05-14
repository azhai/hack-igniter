<?php
/**
 * hack-igniter
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @package hack-igniter
 * @author  Ryan Liu (azhai)
 * @link    http://azhai.surge.sh/
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

defined('BASEPATH') or exit('No direct script access allowed');
$loader = load_class('Loader', 'core');
$loader->name_space('Faker', VENDPATH . 'fzaninotto/faker/src/Faker');


/**
 * 往表中填充数据
 */
class Fake_page extends MY_Controller
{
    protected static $faker = null;
    public $generators = [];

    public static function get_faker($locale = 'zh_CN')
    {
        if (!self::$faker) {
            self::$faker = Faker\Factory::create($locale);
        }
        return self::$faker;
    }

    public function initialize()
    {
        parent::initialize();
        $files = glob(APPPATH . 'models/fakes/*_fake.php');
        foreach ($files as $file) {
            $name = basename($file, '_fake.php') . '_model';
            $this->generators[$name] = (include $file);
        }
    }

    public function create($generator, $count = 1)
    {
        $rows = [];
        for (; $count > 0; $count --) {
            $row = [];
            foreach ($generator as $col => $gen) {
                $row[$col] = $gen();
            }
            $rows[] = $row;
        }
        return $rows;
    }

    public function create_model($model, $count = 1)
    {
        $model = ucfirst($model);
        if (isset($this->generators[$model])) {
            return [];
        }
        $generator = $this->generators[$model];
        return $this->create($generator, $count);
    }

    public function index($count = 10)
    {
        if (!is_cli()) {
            exit('Only run in CLI mode');
        }
        $total = 0;
        foreach ($this->generators as $name => $generator) {
            $name = lcfirst($name);
            $rows = $this->create($generator, $count);
            $this->load->model('default/' . $name);
            $total += $this->$name->insert_batch($rows);
        }
        return $total;
    }
}
