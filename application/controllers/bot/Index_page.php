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
require_once dirname(__DIR__) . '/Event_page.php';

$loader = load_class('Loader', 'core');
$loader->name_space('CodeRefactor', VENDPATH . 'azhai/code-refactor/src/CodeRefactor/');
$loader->name_space('PhpParser', VENDPATH . 'nikic/php-parser/lib/PhpParser/');

use CodeRefactor\Refactor;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * 模拟请求接口并记录数据
 */
class Index_page extends Event_page
{
    const PATH_INFO_INDEX = '/bot/index';
    protected $response_type = 'json';

    public function index()
    {
        $base_url = 'http://api.xiaoxinganapp.cn';
        $this->event->emit('curl.init', [$base_url]);
        $path_info = $this->input->server('PATH_INFO');
        if (starts_with($path_info, self::PATH_INFO_INDEX)) {
            $path_info = substr($path_info, strlen(self::PATH_INFO_INDEX));
        }
        $url = '/' . trim($path_info, '/') . '.php';
        //发送请求到真实的网址
        $headers = $this->input->request_headers();
        foreach ($headers as $key => $value) {
            if (starts_with($key, 'X-Api-')) {
                $this->client->setHeader($key, $value);
            }
        }
        $data = $this->input->get();
        if ('GET' === $this->input->method(true)) {
            $result = $this->client->getJSON($url, $data);
        } else {
            $data = array_replace($data, $this->input->post());
            $result = $this->client->postJson($url, $data);
        }
        //创建对应的控制器和方法
        $pieces = explode('/', trim($path_info, '/'));
        if (2 === count($pieces)) {
            $name = ucfirst($pieces[0]) . '_page';
            $this->create_file($name, false);
            $this->add_method($name, $pieces[1], $result);
        }
        return $result;
    }

    public function create_file($name, $override = false)
    {
        $filename = APPPATH . 'controllers/api/' . $name . '.php';
        if ($override || !file_exists($filename) || filesize($filename) < 300) {
            $context = ['name' => $name, 'title' => '控制器'];
            $context['result'] = [
                "errno" => 0,
                "content" => "success",
            ];
            $tpl_file = $this->get_template('template');
            $content = $this->render_html($tpl_file, $context);
            $content = "<?php\n" . trim($content);
            file_put_contents($filename, $content);
        }
    }

    public function add_method($name, $method, $result)
    {
        $filename = APPPATH . 'controllers/api/' . $name . '.php';
        $ref = new Refactor([
            'phpVersion' => 'PREFER_PHP5',
            'shortArraySyntax' => true,
            'alternativeSyntax' => true,
        ]);
        $codes = $ref->parseFiles([$filename]);
        $class = $codes[$filename]->getClass($name);
        $node = new ClassMethod($method, ['flags' => Class_::MODIFIER_PUBLIC]);
        $body = <<<'EOD'
$result = {{ RESULT }};
return $this->lb_output(0, 'success', $result);
EOD;
        $body = str_replace('{{ RESULT }}', var_export($result, true), $body);
        $node->stmts = $ref->parseCode($body);
        $class->setMethod($method, $node);
        $ref->writeFile($filename);
    }

    public function dump($input)
    {
        var_export($input->server('PATH_INFO'));
        echo "\n\n";
        var_export($input->method(true));
        echo "\n\n";
        var_export($input->get());
        echo "\n\n";
        var_export($input->post());
        echo "\n\n";
        var_export($input->request_headers());
        echo "\n\n";
    }
}
