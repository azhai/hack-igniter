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

require_once VENDPATH . 'autoload.php';
//$loader = load_class('Loader', 'core');
//$loader->name_space('InfluxDB', VENDPATH . 'influxdb/influxdb-php/src');


/**
 * InfluxDB Result Class
 *
 * This class extends the parent result class: CI_DB_result
 *
 * @link        https://github.com/influxdata/influxdb-php
 */
class CI_DB_influxdb_result extends CI_DB_result
{

    protected $is_read_series = false;
    protected $result_series = [];
    protected $serie_counts = [];
    protected $serie_columns = null;
    protected $serie_tags = null;

    /**
     * The query result set
     *
     * @return    array
     */
    public function get_series()
    {
        if ($this->is_read_series) {
            return $this->result_series;
        }
        $this->result_series = $this->result_id->getSeries();
        if (empty($this->result_series)) {
            $this->result_series = [];
            $this->num_rows = 0;
        }
        $this->is_read_series = true;
        return $this->result_series;
    }

    /**
     * @param  array $serie
     * @return array
     */
    public function get_points(array $serie)
    {
        if ($this->result_id) {
            return $this->result_id->getPointsFromSerie($serie);
        }
        $points = [];
        foreach ($serie['values'] as $value) {
            $point = array_combine($serie['columns'], $value);
            if (isset($serie['tags'])) {
                $point += $serie['tags'];
            }
            $points[] = $point;
        }
        return $points;
    }

    /**
     * @param  array $serie
     * @return array
     */
    public function fetch_point(array $serie, $offset = 0, $with_tags = false)
    {
        $count = isset($serie['values']) ? count($serie['values']) : 0;
        if ($count === 0 || $offset >= $count || $offset < 0 - $count) {
            return [];
        }
        $offset = ($offset < 0) ? $offset + $count : (int)$offset;
        $point = array_combine($serie['columns'], $serie['values'][$offset]);
        if ($with_tags && isset($serie['tags'])) {
            $point += $serie['tags'];
        }
        return $point;
    }

    /**
     * Number of rows in the result set
     *
     * @return    int
     */
    public function num_rows()
    {
        if (is_int($this->num_rows)) {
            return $this->num_rows;
        }
        $series = $this->get_series();
        foreach ($series as $i => $s) {
            $count = isset($s['values']) ? count($s['values']) : 0;
            $this->serie_counts[] = $count;
            if (0 === (int)$i) {
                $this->serie_columns = isset($s['columns']) ? $s['columns'] : [];
                $this->serie_tags = isset($s['tags']) ? array_keys($s['tags']) : [];
            }
        }
        $this->num_rows = array_sum($this->serie_counts);
        return $this->num_rows;
    }

    // --------------------------------------------------------------------

    /**
     * Number of fields in the result set
     *
     * @return    int
     */
    public function num_fields()
    {
        return count($this->list_fields());
    }

    // --------------------------------------------------------------------

    /**
     * Fetch Field Names
     *
     * Generates an array of column names
     *
     * @return    array
     */
    public function list_fields()
    {
        if (!is_null($this->serie_columns) && !is_null($this->serie_tags)) {
            return array_merge($this->serie_columns, $this->serie_tags);
        }
        if ($series = $this->get_series()) {
            $s = $series[0];
            $this->serie_columns = isset($s['columns']) ? $s['columns'] : [];
            $this->serie_tags = isset($s['tags']) ? array_keys($s['tags']) : [];
            return array_merge($this->serie_columns, $this->serie_tags);
        } else {
            $this->serie_columns = [];
            $this->serie_tags = [];
            return [];
        }
    }

    // --------------------------------------------------------------------

    /**
     * Field data
     *
     * Generates an array of objects containing field meta-data
     *
     * @return    array
     */
    public function field_data()
    {
        $this->list_fields();
        $result = [];
        foreach ($this->serie_columns as $column) {
            $field = new stdClass();
            $field->name = $column;
            $field->type = 'string';
            $result[] = $field;
        }
        foreach ($this->serie_tags as $tag) {
            $field = new stdClass();
            $field->name = $tag;
            $field->type = 'tag';
            $result[] = $field;
        }
        return $result;
    }

    // --------------------------------------------------------------------

    /**
     * Data Seek
     *
     * Moves the internal pointer to the desired offset. We call
     * this internally before fetching results to make sure the
     * result set starts at zero.
     *
     * @param int $n
     * @return    bool
     */
    public function data_seek($n = 0)
    {
        $this->current_row = (int)$n;
        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Result - associative array
     *
     * Returns the result set as an array
     *
     * @return    array
     */
    protected function _fetch_assoc()
    {
        $row = [];
        if ($this->current_row >= $this->num_rows()) {
            return $row;
        }
        $n = $this->current_row;
        foreach ($this->serie_counts as $i => $count) {
            if ($n >= $count) {
                $n -= $count;
                continue;
            }
            $serie = $this->result_series[$i];
            $row = $this->fetch_point($serie, $n);
            break;
        }
        $this->current_row++;
        return $row;
    }

    // --------------------------------------------------------------------

    /**
     * Result - object
     *
     * Returns the result set as an object
     *
     * @param string $class_name
     * @return    object
     */
    protected function _fetch_object($class_name = 'stdClass')
    {
        return new ArrayObject($this->_fetch_assoc());
    }

}
