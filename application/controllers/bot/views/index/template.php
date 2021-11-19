defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(__DIR__) . '/Base_page.php';


/**
* <?php echo $title."\n"; ?>
*/
class <?php echo $name; ?> extends Base_page
{
public function __call($method, $args)
{
require_once __DIR__ . '/setting/' . $method . '.php';
$obj = new $method();
return $obj->index();
}

public function index()
{
$result = <?php echo var_export($result, true); ?>;
return $this->lb_output(0, 'success', $result);
}
}
