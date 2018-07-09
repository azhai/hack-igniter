<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * 账户
 */
class Account_model extends MY_Model
{
    protected $_db_key = 'default';
    protected $_db_key_ro = 'default_ro';
    protected $_table_name = 't_accounts';
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
            'user_id' => 'int',
            'balance' => 'int',
            'currency' => 'varchar',
            'created_at' => 'timestamp',
            'changed_at' => 'timestamp',
            'is_removed' => 'tinyint',
        ];
    }

    public function increase_amount($where, $amount)
    {
        $amount = intval($amount);
        $changes = ['balance' => 'balance + ' . $amount];
        $this->trans_start();
        $result = $this->update($changes, $where, 1, false);
        if ($result) {
            $account = $this->one($where);
            $data = [
                'account_id' => $account['id'],
                'amount' => $amount,
                'after_balance' => $account['balance'],
                'currency' => $account['currency'],
            ];
            $this->load->model('default/account_history_model');
            $this->account_history_model->insert($data);
        }
        $this->trans_complete();
        return $result;
    }

    public function increase_by_id($id, $amount)
    {
        $where = ['id' => $id];
        return $this->increase_amount($where, $amount);
    }

    public function increase_by_user($user_id, $amount, $currency = 'COIN')
    {
        $where = ['user_id' => $user_id, 'currency' => $currency];
        return $this->increase_amount($where, $amount);
    }
}
