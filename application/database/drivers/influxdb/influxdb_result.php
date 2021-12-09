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

defined('BASEPATH') OR exit('No direct script access allowed');

require_once VENDPATH . 'autoload.php';
//$loader = load_class('Loader', 'core');
//$loader->name_space('InfluxDB', VENDPATH . 'influxdb/influxdb-php/src');


/**
 * InfluxDB Result Class
 *
 * This class extends the parent result class: CI_DB_result
 *
 * @link		https://github.com/influxdata/influxdb-php
 */
class CI_DB_influxdb_result extends CI_DB_result {

	/**
	 * Number of rows in the result set
	 *
	 * @return	int
	 */
	public function num_rows()
	{
		return is_int($this->num_rows)
			? $this->num_rows
			: $this->num_rows = $this->result_id->num_rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Number of fields in the result set
	 *
	 * @return	int
	 */
	public function num_fields()
	{
		return $this->result_id->field_count;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Field Names
	 *
	 * Generates an array of column names
	 *
	 * @return	array
	 */
	public function list_fields()
	{
		$field_names = array();
		$this->result_id->field_seek(0);
		while ($field = $this->result_id->fetch_field())
		{
			$field_names[] = $field->name;
		}

		return $field_names;
	}

	// --------------------------------------------------------------------

	/**
	 * Field data
	 *
	 * Generates an array of objects containing field meta-data
	 *
	 * @return	array
	 */
	public function field_data()
	{
		$retval = array();
		$field_data = $this->result_id->fetch_fields();
		for ($i = 0, $c = count($field_data); $i < $c; $i++)
		{
			$retval[$i]			= new stdClass();
			$retval[$i]->name		= $field_data[$i]->name;
			$retval[$i]->type		= static::_get_field_type($field_data[$i]->type);
			$retval[$i]->max_length		= $field_data[$i]->max_length;
			$retval[$i]->primary_key	= (int) ($field_data[$i]->flags & INFLUXDB_PRI_KEY_FLAG);
			$retval[$i]->default		= $field_data[$i]->def;
		}

		return $retval;
	}

	// --------------------------------------------------------------------

	/**
	 * Get field type
	 *
	 * Extracts field type info from the bitflags returned by
	 * influxdb_result::fetch_fields()
	 *
	 * @used-by	CI_DB_influxdb_result::field_data()
	 * @param	int	$type
	 * @return	string
	 */
	private static function _get_field_type($type)
	{
		static $map;
		isset($map) OR $map = array(
			INFLUXDB_TYPE_DECIMAL     => 'decimal',
			INFLUXDB_TYPE_BIT         => 'bit',
			INFLUXDB_TYPE_TINY        => 'tinyint',
			INFLUXDB_TYPE_SHORT       => 'smallint',
			INFLUXDB_TYPE_INT24       => 'mediumint',
			INFLUXDB_TYPE_LONG        => 'int',
			INFLUXDB_TYPE_LONGLONG    => 'bigint',
			INFLUXDB_TYPE_FLOAT       => 'float',
			INFLUXDB_TYPE_DOUBLE      => 'double',
			INFLUXDB_TYPE_TIMESTAMP   => 'timestamp',
			INFLUXDB_TYPE_DATE        => 'date',
			INFLUXDB_TYPE_TIME        => 'time',
			INFLUXDB_TYPE_DATETIME    => 'datetime',
			INFLUXDB_TYPE_YEAR        => 'year',
			INFLUXDB_TYPE_NEWDATE     => 'date',
			INFLUXDB_TYPE_INTERVAL    => 'interval',
			INFLUXDB_TYPE_ENUM        => 'enum',
			INFLUXDB_TYPE_SET         => 'set',
			INFLUXDB_TYPE_TINY_BLOB   => 'tinyblob',
			INFLUXDB_TYPE_MEDIUM_BLOB => 'mediumblob',
			INFLUXDB_TYPE_BLOB        => 'blob',
			INFLUXDB_TYPE_LONG_BLOB   => 'longblob',
			INFLUXDB_TYPE_STRING      => 'char',
			INFLUXDB_TYPE_VAR_STRING  => 'varchar',
			INFLUXDB_TYPE_GEOMETRY    => 'geometry'
		);

		return isset($map[$type]) ? $map[$type] : $type;
	}

	// --------------------------------------------------------------------

	/**
	 * Free the result
	 *
	 * @return	void
	 */
	public function free_result()
	{
		if (is_object($this->result_id))
		{
			$this->result_id->free();
			$this->result_id = FALSE;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Data Seek
	 *
	 * Moves the internal pointer to the desired offset. We call
	 * this internally before fetching results to make sure the
	 * result set starts at zero.
	 *
	 * @param	int	$n
	 * @return	bool
	 */
	public function data_seek($n = 0)
	{
		return $this->result_id->data_seek($n);
	}

	// --------------------------------------------------------------------

	/**
	 * Result - associative array
	 *
	 * Returns the result set as an array
	 *
	 * @return	array
	 */
	protected function _fetch_assoc()
	{
		return $this->result_id->fetch_assoc();
	}

	// --------------------------------------------------------------------

	/**
	 * Result - object
	 *
	 * Returns the result set as an object
	 *
	 * @param	string	$class_name
	 * @return	object
	 */
	protected function _fetch_object($class_name = 'stdClass')
	{
		return $this->result_id->fetch_object($class_name);
	}

}
