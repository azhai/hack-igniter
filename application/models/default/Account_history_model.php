<?php
defined('BASEPATH') || exit('No direct script access allowed');


/**
 * 账户流水
 */
class Account_history_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_account_history';
    protected $_created_field = 'created_at';
    protected $_changed_field = 'changed_at';

    public function table_indexes($another = false)
    {
        return ['id'];
    }

    public function table_fields()
    {
        return [
            'id' => 'int',
            'account_id' => 'int',
            'amount' => 'int',
            'balance' => 'int',
            'currency' => 'varchar',
            'cashier' => 'varchar',
            'remark' => 'text',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }
}
