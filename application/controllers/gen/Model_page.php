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
require_once APPPATH . 'config/' . constant('ENVIRONMENT') . '/database.php';

/**
 * 为每张表生成一个Model文件
 */
class Model_page extends MY_Controller
{
    protected $tables = [];
    protected $mixins = [];
    public $override = false;
    public $singular = true;

    public static function file_not_found($filename)
    {
        return !file_exists($filename) || 0 === filesize($filename);
    }

    public function index()
    {
        if (!is_cli()) {
            exit('Only run in CLI mode');
        }
        $this->load->helper('inflector');
        $this->load->helper('format');
        $this->tables = [];
        $this->mixins = [];
        $this->init_mixins();
        $this->create_models();
        $this->finish();
        return $this->tables;
    }

    public function init_mixins()
    {
        $files = glob(APPPATH . 'models/*/*_mixin.php');
        $pos = strlen(APPPATH . 'models/');
        foreach ($files as $file) {
            $mixin = ['file' => substr($file, $pos)];
            $mixin['name'] = basename($mixin['file'], '.php');
            $model_name = substr($mixin['name'], 0, -6) . '_model';
            $this->mixins[$model_name] = $mixin;
        }
        return $this->mixins;
    }

    public function create_models()
    {
        foreach ($GLOBALS['db'] as $db_key => $conf) {
            if (ends_with($db_key, '_ro')) {
                continue;
            }
            $sub_dir = $db_key;
            if (defined('DB_TABLE_PREFIX') && DB_TABLE_PREFIX) {
                if (starts_with($sub_dir, DB_TABLE_PREFIX)) {
                    $sub_dir = substr($sub_dir, strlen(DB_TABLE_PREFIX));
                }
            }
            $full_path = APPPATH . 'models/' . $sub_dir . '/';
            @mkdir($full_path);
            $prefix = $conf['dbprefix'];
            $tables = $this->write_db_models($full_path, $db_key, $prefix);
            $this->tables[$sub_dir] = $tables;
            echo $sub_dir . "\n";
        }
    }

    public function write_db_models($full_path, $db_key, $prefix = '')
    {
        $db = $this->load->database($db_key, true);
        $tables = $db->list_tables();
        $prelen = strlen($prefix);
        $result = [];
        foreach ($tables as $table) {
            if (!starts_with($table, $prefix)) {
                continue;
            }
            if (ends_with($table, '_bak') || ends_with($table, '_copy')) {
                continue;
            }
            if (is_numeric(substr($table, -4))) {
                continue;
            }
            $name = ucfirst(substr($table, $prelen));
            if ($this->singular) {
                $name = singular($name);
            }
            $model_name = $name . '_model';
            $filename = $full_path . $model_name . '.php';
            if ($this->override || self::file_not_found($filename)) {
                $columns = $db->field_data($table);
                $context = [
                    'name' => $model_name, 'table' => $table,
                    'db_key' => $db_key, 'mixin' => [],
                ];
                $context['title'] = $this->get_table_comment($db, $table);
                if (isset($this->mixins[$model_name])) {
                    $context['mixin'] = $this->mixins[$model_name];
                }
                $this->write_model($filename, $columns, $context);
                $result[] = $table;
            }
        }
        return $result;
    }

    protected function write_model($filename, array $columns, array $context)
    {
        $context['pkeys'] = $context['fields'] = [];
        foreach ($columns as $c) {
            $context['fields'][$c->name] = $c->type;
            if ($c->primary_key) {
                $context['pkeys'][] = $c->name;
            }
        }
        if (empty($context['title'])) {
            $context['title'] = $context['name'];
        }
        if ($mixin = $context['mixin']) {
            require_once APPPATH . 'models/' . $mixin['file'];
            $context['mixin']['methods'] = get_class_methods($mixin['name']);
        }
        $tpl_file = $this->get_template('template');
        $content = $this->render_html($tpl_file, $context);
        $content = "<?php\n" . trim($content);
        file_put_contents($filename, $content);
    }

    protected function get_table_comment($db, $table)
    {
        $sql = "SELECT `table_comment` FROM `INFORMATION_SCHEMA`.`TABLES`"
            . " WHERE `table_schema`='%s' AND `table_name`='%s'";
        $result = $db->query(sprintf($sql, $db->database, $table));
        if ($obj = $result->row()) {
            return $obj->table_comment;
        }
    }

    public function finish()
    {
    }
}
