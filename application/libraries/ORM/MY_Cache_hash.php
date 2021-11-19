<?php
/**
 * hack-igniter.
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @author  Ryan Liu (azhai)
 *
 * @see    http://azhai.surge.sh/
 *
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Mylib\ORM;

/**
 * 扩展Model，缓存为hash.
 */
trait MY_Cache_hash
{
    use MY_Cacheable;

    public function cache_type()
    {
        return 'hash';
    }

    public function get_as($value, $type = '')
    {
        $where = $this->condition($value);
        if (empty($where)) {
            return;
        }
        $row = $this->cache_subject()->read_cache($where);
        if (empty($row)) {
            if ($fields = $this->cache_fields()) {
                $this->select(array_keys($fields));
            }
            $result = $this->one($where, $type);
            $data = $this->states($result);
            $this->cache_subject()->write_cache($where, $data);
        } elseif ('array' === $type) {
            $result = $row;
        } else {
            $result = empty($type) ? clone $this : new $type();
            foreach ($row as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function get_array($value)
    {
        return $this->get_as($value, 'array');
    }

    public function get_in_array($values, $key = '')
    {
        $result = [];
        $remains = [];
        //找出已缓存的
        foreach ($values as $value) {
            if (empty($key)) {
                $where = $this->condition($value);
            } else {
                $where = [$key => $value];
            }
            $row = $this->cache_subject()->read_cache($where);
            //保持$result和$values相同的次序
            if (empty($row)) {
                $remains[] = $value;
                $result[$value] = [];
            } else {
                $result[$value] = $row;
            }
        }
        if (empty($remains)) {
            return $result;
        }
        //读数据库和缓存剩下的
        if (empty($key)) {
            $key = key($where);
        }
        $chunks = array_chunk($remains, 800);
        foreach ($chunks as $chunk) {
            if ($fields = $this->cache_fields()) {
                $this->select(array_keys($fields));
            }
            $rows = $this->parse_where([$key => $chunk])->all();
            foreach ($rows as $row) {
                $value = $row[$key];
                $result[$value] = $row;
                $where = [$key => $value];
                $data = $this->states($row);
                $this->cache_subject()->write_cache($where, $data);
            }
        }

        return $result;
    }

    public function save($row = null, $key = null, $escape = null)
    {
        $row = $row ? to_array($row) : $this->status();
        if (\is_string($key) && isset($row[$key])) {
            $where = [$key => $row[$key]];
        } elseif (\is_array($key)) {
            $where = $key;
        } else {
            $where = $this->condition();
        }
        if ($where) { //先尝试更新
            $result = $this->update($row, $where, 1, $escape);
            if ($result) {
                return $result;
            }
        }

        return $this->insert($row, true, $escape);
    }
}
