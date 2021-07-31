defined('BASEPATH') OR exit('No direct script access allowed');
<?php if ($mixin):
    echo 'require_once APPPATH . \'models/' . $mixin['file'] . '\';' . "\n";
endif; ?>


/**
* <?= $title . "\n" ?>
*/
class <?= $name ?> extends MY_Model
{
<?php if ($mixin):
    echo '    use \\' . $mixin['name'] . ';' . "\n\n";
endif; ?>
    protected $_db_key = '<?= $db_key ?>';
    protected $_db_key_ro = '<?= $db_key ?>';
    protected $_table_name = '<?= $table ?>';
<?php if (isset($fields['created_at'])):
    echo "    protected \$_created_field = 'created_at';\n";
endif; ?>
<?php if (isset($fields['changed_at'])):
    echo "    protected \$_changed_field = 'changed_at';\n";
endif; ?>

<?php if (!$mixin || !in_array('table_indexes', $mixin['methods'], true)): ?>
    public function table_indexes($another = false)
    {
        return ['<?= implode("', '", $pkeys) ?>'];
    }
<?php endif; ?>

<?php if (!$mixin || !in_array('table_fields', $mixin['methods'], true)): ?>
    public function table_fields()
    {
        return <?= array_export($fields, 8) ?>;
    }
<?php endif; ?>
}
