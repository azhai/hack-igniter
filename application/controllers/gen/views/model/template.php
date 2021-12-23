defined('BASEPATH') OR exit('No direct script access allowed');
<?php if ($mixin) {
    echo 'require_once APPPATH . \'models/'.$mixin['file'].'\';'."\n";
} ?>


/**
* <?php echo $title."\n"; ?>
*/
class <?php echo $name; ?> extends MY_Model
{
<?php if ($mixin) {
    echo '    use \\'.$mixin['name'].';'."\n\n";
} ?>
    protected $_db_key = '<?php echo $db_key; ?>';
    protected $_db_key_ro = '<?php echo $db_key; ?>';
    protected $_table_name = '<?php echo $table; ?>';
<?php if (isset($fields['created_at'])) {
    echo "    protected \$_created_field = 'created_at';\n";
} ?>
<?php if (isset($fields['changed_at'])) {
    echo "    protected \$_changed_field = 'changed_at';\n";
} ?>

<?php if (! $mixin || ! in_array('table_indexes', $mixin['methods'], true)) {
    ?>
    public function table_indexes($another = false)
    {
        return ['<?php echo implode("', '", $pkeys); ?>'];
    }
<?php
} ?>

<?php if (! $mixin || ! in_array('table_fields', $mixin['methods'], true)) {
        ?>
    public function table_fields()
    {
        return <?php echo array_export($fields, 8); ?>;
    }
<?php
    } ?>
}
