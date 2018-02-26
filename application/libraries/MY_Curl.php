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
$loader = load_class('Loader', 'core');
$loader->name_space('Psr\\Log', VNDPATH . 'psr/log/Psr/Log');
$loader->name_space('Unirest', VNDPATH . 'mashape/unirest-php/src/Unirest');

class_alias('\\Unirest\\Request', 'UniRequest');


/**
 * cURL的HTTP客户端
 */
class MY_Curl
{
    use \Psr\Log\LoggerAwareTrait;

    const ERRNO_RESOLVE_FAIL = 6;
    const ERRNO_CONN_FAIL = 7;
    const ERRNO_DNS_FAIL = 28;

    const MAX_TRY_TIMES = 3;    //同一请求最大发送次数
    const SLEEP_SECONDS = 0.3;  //同一请求发送休息间隔

    public $retry_times = 0;
    protected $base_url = '';    //备份全局options
    protected $global_opts = [];     //最后一次请求结果，会覆盖
    protected $response = null;

    public function __construct(array $config = null)
    {
        if ($config && isset($config['base_url'])) {
            $this->set_base_url($config['base_url']);
        }
    }

    public function set_base_url($base_url)
    {
        $this->base_url = rtrim($base_url, '/');
        return $this;
    }

    /**
     * 执行get/post/put/delete/header/option等操作.
     * $args中的参数次序为 url, headers, body, ...
     *
     * @return Unirest\Response对象
     */
    public function __call($name, $args)
    {
        $this->prepare();
        @list($url, $headers, $body) = $args;
        $url = $args[0] = $this->get_url_string($url);
        $headers = $args[1] = empty($headers) ? [] : $headers;
        $this->retry_times = 0;
        do {
            $phrase = $this->send('UniRequest', $name, $args);
            if (empty($phrase)) { //无错
                break;
            } else {
                $this->retry_times++;
                usleep(self::SLEEP_SECONDS * 1.0E6);
            }
        } while ($this->retry_times < self::MAX_TRY_TIMES);
        $body = self::get_body_string($body);
        $method = self::get_request_method($name);
        $this->finish($method, $url, $body, $headers, $phrase);
        return $this->response;
    }

    /**
     * 加入options
     */
    public function prepare(array $options = [])
    {
        if (!array_key_exists('timeout', $options) && !array_key_exists('Timeout', $options)
        ) {
            $options['Timeout'] = intval(ini_get('default_socket_timeout'));
        }
        if (!array_key_exists('useragent', $options) && !array_key_exists('UserAgent', $options)
        ) {
            $options['UserAgent'] = 'Mozilla/4.0';
        }
        if (empty($this->global_opts)) { //未保存过
            $this->global_opts = UniRequest::curlOpts([]);
        }
        if (!empty($options)) {
            UniRequest::curlOpts($this->global_opts);
        }
        return $this;
    }

    public function get_url_string($url)
    {
        $url = is_string($url) ? ltrim($url, '/') : '';
        if (!empty($this->base_url)) {
            $url = $this->base_url . '/' . $url;
        }
        return $url;
    }

    /**
     * 发送访问请求
     * 网络出错时替换域名为IP，预备下次重试.
     *
     * @param string $request Unirest\Request类名
     * @param string $name HTTP方法名
     * @param array $args 参数列表
     * @return string 错误描述
     */
    public function send($request, $name, array & $args = [])
    {
        $phrase = '';
        try {
            $this->response = exec_method_array($request, $name, $args);
        } catch (\Exception $e) {
            $phrase = $e->getMessage();
            @list($url, $headers) = $args;
            $url = empty($url) ? '' : $url;
            $headers = empty($headers) ? [] : $headers;
            if (!isset($headers['Host']) && self::is_unreachable()) {
                $headers['Host'] = self::instead_of_url($url);
                array_splice($args, 0, 2, [$url, $headers]);
            }
        }
        return $phrase;
    }

    /**
     * 请求是否遇到网络不可达（如域名解析失败）的错误.
     *
     * @return bool
     */
    public static function is_unreachable()
    {
        $failures = [
                self::ERRNO_RESOLVE_FAIL,
                self::ERRNO_DNS_FAIL,
                self::ERRNO_CONN_FAIL,
        ];
        if ($errno = self::get_error_no()) {
            return in_array($errno, $failures, true);
        }
        return false;
    }

    public static function get_error_no()
    {
        if ($handler = UniRequest::getCurlHandle()) {
            return curl_errno($handler);
        }
    }

    /**
     * 使用指定的IP代替域名访问.
     *
     * @param string $url 访问的网址，传址
     * @return string 解析得到的域名
     */
    public static function instead_of_url(& $url)
    {
        $hostname = parse_url($url, PHP_URL_HOST);
        if ($ipaddr = self::get_domain_ip($hostname)) {
            $url = str_ireplace($hostname, $ipaddr, $url);
        }
        return $hostname;
    }

    /**
     * 解析出正确的IP，用于域名访问超时.
     *
     * @return string 域名对应IP地址
     */
    public static function get_domain_ip($domain)
    {
        $dns_records = dns_get_record($domain, DNS_A, $authns, $addtl);
        if ($dns_records && $ipaddr = $dns_records[0]['ip']) {
            return $ipaddr; //使用解析到的IP
        }
    }

    public static function get_body_string($body)
    {
        if (empty($body)) {
            $body = '-';
        } elseif (is_array($body) || $body instanceof \Traversable) {
            $body = UniRequest::buildHTTPCurlQuery($body);
            $body = http_build_query($body);
        }
        return $body;
    }

    public static function get_request_method($method = 'GET')
    {
        return @constant('\\Unirest\\Method::' . strtoupper($method));
    }

    /**
     * 还原options和记录日志
     */
    public function finish(
        $method = 'GET',
        $url = '-',
                           $reqbody = '-',
        $headers = [],
        $phrase = ''
    ) {
        UniRequest::clearCurlOpts();
        UniRequest::curlOpts($this->global_opts);
        if (isset($this->logger)) {
            $log_func = [$this->logger, 'log'];
        } elseif (function_exists('log_message')) {
            $log_func = 'log_message';
        } else {
            $log_func = null;
        }
        if ($log_func) {
            $code = $this->get_status_code();
            $respbody = $this->get_response_body('UTF-8');
            if ($this->response) {
                //$url = UniRequest::getInfo(CURLINFO_EFFECTIVE_URL); //最终URL
                $connect_time = UniRequest::getInfo(CURLINFO_CONNECT_TIME);
                $total_time = UniRequest::getInfo(CURLINFO_TOTAL_TIME);
            } else {
                $connect_time = 0;
                $total_time = 0;
            }
            $headers = empty($headers) ? '' : json_encode($headers) . "\n";
            $phrase .= ($phrase ? "\n" : '');
            $message = "{$method} \"{$url}\" {$connect_time} {$total_time} {$code}"
                . "\n{$headers}{$phrase}>>>>>>>>\n{$reqbody}\n<<<<<<<<\n{$respbody}\n";
            call_user_func($log_func, 'DEBUG', $message);
        }
    }

    /**
     * 最后一次状态码
     */
    public function get_status_code()
    {
        if ($this->response) {
            return $this->response->code;
        } else {
            return -1;
        }
    }

    /**
     * 最后一次输出
     */
    public function get_response_body($encoding = '')
    {
        if ($this->response && $this->response->raw_body) {
            $body = $this->response->raw_body;
            if ($encoding) {
                $body = convert_string($body, $encoding);
            }
            return $body;
        } else {
            return '-';
        }
    }

    /**
     * 最后一次调用是否成功
     */
    public function is_success()
    {
        $code = $this->get_status_code();
        return $code >= 100 && $code < 400;
    }

    /**
     * 转为JSON格式
     */
    public function to_json()
    {
        $respbody = $this->get_response_body();
        if ($respbody && $respbody !== '-') {
            return json_decode($respbody, true);
        }
    }
}
