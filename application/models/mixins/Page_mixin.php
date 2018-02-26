<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Page
 */
trait Page_mixin
{
    use \Mylib\Behavior\MY_Foreign;

    public function get_relations()
    {
        return [
            'owner' => [
                'model' => 'default/user_model',
                'fkey' => 'ownerid',
            ],
        ];
    }
}
