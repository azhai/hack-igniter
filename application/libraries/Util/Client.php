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

namespace Mylib\Util;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

$loader = load_class('Loader', 'core');
$loader->name_space('Psr\\Log', VENDPATH.'psr/log/Psr/Log');
$loader->name_space('Curl', VENDPATH.'php-curl-class/php-curl-class/src');

// 域名解析失败错误
const ERRNO_RESOLVE_FAIL = 6;
const ERRNO_CONN_FAIL = 7;
const ERRNO_DNS_FAIL = 28;

/**
 * cURL客户端
 * Example:
 *   $c = new \Mylib\Util\Client('http://127.0.0.1/api/', 3);
 *   $c->timer = new \Mylib\Util\Timer(7, 1);
 *   $c->setContentType('json');
 *   $c->append('time.php', ['now' => time()]);.
 */
class Client
{
    use LoggerAwareTrait;

    public $is_multi = false;
    public $batch_data = [];
    public $timer;
    protected $curl;
    protected static $methods = [
        'delete', 'download', 'get', 'head',
        'options', 'patch', 'post', 'put', 'search',
    ];

    /**
     * 构造函数，创建cURL的HTTP客户端
     * NOTICE:
     *   PHP的cURL无法看到本地的/etc/hosts文件，而Bash的curl可以.
     *
     * @param string $base_url    = null 网址前缀
     * @param int    $concurrency = 0 并发
     */
    public function __construct($base_url = null, $concurrency = 0)
    {
        if ($concurrency > 0) {
            $this->curl = new Curl\MultiCurl($base_url);
            $this->curl->setConcurrency($concurrency);
            $this->is_multi = true;
        } else {
            $this->curl = new Curl\Curl($base_url);
        }
        //$this->curl->setRetry(replaceHostCallback(false, 443));
        //$this->curl->success(logSimpleCallback(true));
        //$this->curl->error(logSimpleCallback());
    }

    /**
     * 析构函数.
     */
    public function __destruct()
    {
        if ($this->curl) {
            $this->batchSend(array_splice($this->batch_data, 0));
            $this->curl->close();
        }
    }

    /**
     * 调用其他函数.
     *
     * @param mixed $name
     * @param mixed $args
     */
    public function __call($name, $args)
    {
        $name = strtolower($name);
        if ($this->is_multi && \in_array($name, self::$methods, true)) {
            $name = 'add'.ucfirst($name);
        }

        return exec_method_array($this->curl, $name, $args);
    }

    /**
     * 设置日志方法.
     *
     * @param mixed $log
     * @param mixed $verbose
     */
    public function setLogMethod($log = 'log_message', $verbose = false)
    {
        if (empty($log) || \is_string($log) && ! \function_exists($log)) {
            return;
        }
        if (\is_array($log) && $log[0] instanceof LoggerInterface) {
            $this->setLogger($log[0]);
        }
        if ($verbose) {
            $this->curl->success(logVerboseCallback($log, true, 'DEBUG'));
            $this->curl->error(logVerboseCallback($log, false, 'ERROR'));
        } else {
            $this->curl->success(logSimpleCallback($log, true, 'DEBUG'));
            $this->curl->error(logSimpleCallback($log, false, 'ERROR'));
        }
    }

    public function getHtml($url, $data = '')
    {
        $this->setContentType('html');

        return $this->send($url, $data, 'get');
    }

    public function getJson($url, array $data = [])
    {
        $this->setContentType('json');

        return $this->send($url, $data, 'get');
    }

    public function postHtml($url, $data = '')
    {
        $this->setContentType('html');

        return $this->send($url, $data, 'post');
    }

    public function postJson($url, array $data = [])
    {
        $this->setContentType('json');

        return $this->send($url, $data, 'post');
    }

    /**
     * 发送消息.
     *
     * @param string $url    网址
     * @param mixed  $data   数据，json类型直接传递关联数组
     * @param mixed  $method
     *
     * @return object/null
     */
    public function send($url, $data = '', $method = 'post')
    {
        if ($this->is_multi) {
            $method = 'add'.$method;
            $this->curl->{$method}($url, $data);
            $this->curl->start();
        } else {
            $this->curl->{$method}($url, $data);

            return $this->curl->getResponse();
//            $this->curl->close();
        }
    }

    /**
     * 批量发送消息.
     *
     * @param string $url        网址
     * @param array  $batch_data 多条数据
     * @param mixed  $method
     */
    public function batchSend($url, array $batch_data, $method = 'post')
    {
        if (empty($batch_data)) {
            return;
        }
        if ($this->is_multi) {
            $method = 'add'.$method;
            foreach ($batch_data as $data) {
                $this->curl->{$method}($url, $data);
            }
            $this->curl->start();
        } else {
            foreach ($batch_data as $data) {
                $this->curl->{$method}($url, $data);
            }
//            $this->curl->close();
        }
    }

    /**
     * 累积一定数量的消息一起发送
     *
     * @param mixed $url
     * @param mixed $method
     */
    public function append($url, array $data, $method = 'post')
    {
        if (! $this->is_multi || null === $this->timer) {
            return false;
        }
        $this->batch_data[] = $data;
        if ($this->timer->isHitting()) {
            $batch_data = array_splice($this->batch_data, 0);
            $this->batchSend($url, $batch_data, $method);

            return true;
        }

        return false;
    }

    public function addCookie($name, $value)
    {
        $this->curl->setCookie($name, $value);
    }

    public function setCookieFile($filename)
    {
        if (file_exists($filename)) {
            $this->curl->setCookieFile($filename);
            $this->curl->setCookieJar($filename);
        }
    }

    public function setContentType($ctype)
    {
        $ctype = strtolower($ctype);

        switch ($ctype) {
            case 'json':
                $ctype = 'application/json';

                break;

            case 'html':
                $ctype = 'text/html';

                break;
        }
        $this->curl->setHeader('Content-Type', $ctype);
    }

    /**
     * 最后一次调用是否成功
     */
    public function isSuccess()
    {
        if (! $this->is_multi) {
            $code = $this->curl->getHttpStatusCode();

            return $code >= 100 && $code < 400;
        }
    }
}

/**
 * 是否网络不可达（如域名解析失败）的错误.
 *
 * @param mixed $code
 */
function isErrorUnreachable($code)
{
    static $failures = [
        ERRNO_RESOLVE_FAIL,
        ERRNO_DNS_FAIL,
        ERRNO_CONN_FAIL,
    ];

    return \in_array($code, $failures, true);
}

/**
 * 解析域名对应的IP地址
 *
 * @param mixed $domain
 */
function getDomainIpaddr($domain)
{
    $dns_records = dns_get_record($domain, DNS_A);
    if ($dns_records && $ipaddr = $dns_records[0]['ip']) {
        return $ipaddr; //使用解析到的IP
    }

    return '';
}

/**
 * 读取临时文件内容.
 *
 * @param mixed $temp
 */
function readTempFile($temp)
{
    if (! \is_resource($temp)) {
        return '';
    }
    rewind($temp);
    $content = @stream_get_contents($temp);
    fclose($temp);

    return $content ? $content : '';
}

/**
 * 使用IP地址代替Host
 * 始终使用
 *  $client->beforeSend(replaceHostCallback(true, 443, ['localhost' => '127.0.0.1']));
 * 当域名解析失败时使用
 *  $client->setRetry(replaceHostCallback());.
 *
 * @param mixed $always
 * @param mixed $resolve_port
 */
function replaceHostCallback($always = false, $resolve_port = 0, array $hosts = [])
{
    return function ($client) use ($always, $resolve_port, $hosts) {
        if (! $always && ! isErrorUnreachable($client->getCurlErrorCode())) {
            return false;
        }
        $url = $client->getUrl();
        $hostname = parse_url($url, PHP_URL_HOST);
        $ipaddr = @$hosts[strtolower($hostname)];
        if (empty($ipaddr)) {
            $ipaddr = getDomainIpaddr($hostname);
        }
        if (empty($ipaddr)) {
            return false;
        }
        if ($resolve_port > 0 && is_php('5.5')) {
            $record = sprintf('%s:%d:%s', $hostname, $resolve_port, $ipaddr);
            $client->setOpt(CURLOPT_RESOLVE, [$record]); // need php5.5+
        } else {
            $url = str_ireplace($hostname, $ipaddr, $url);
            $client->setUrl($url);
            $client->setHeader('Host', $hostname);
        }

        return true;
    };
}

/**
 * 记录简短日志
 * $client->success(logSimpleCallback($log, true, 'DEBUG'));
 * $client->error(logSimpleCallback($log, false, 'ERROR'));.
 *
 * @param mixed $success
 * @param mixed $level
 */
function logSimpleCallback(callable $log, $success = false, $level = 'DEBUG')
{
    return function ($client) use ($log, $success, $level) {
        $content = sprintf(
            'REST> ip_addr: %s total_time: %s',
            $client->getInfo(CURLINFO_PRIMARY_IP),
            $client->getInfo(CURLINFO_TOTAL_TIME)
        )."\n";
        $method = $client->getOpt(CURLOPT_CUSTOMREQUEST);
        if (empty($method)) {
            $method = strstr($client->getInfo(CURLINFO_HEADER_OUT), ' ', true);
        }
        $content .= 'REST> '.$method.' '.$client->getUrl()."\n";
        $request = $client->getOpt(CURLOPT_POSTFIELDS);
        $content .= 'REST> '.$request."\n";
        $response = $success ? $client->getRawResponse() : 'ERROR: '.$client->getErrorMessage();
        $content .= 'REST> '.$response."\n";
        \call_user_func($log, $level, $content);
    };
}

/**
 * 记录详细日志
 * $client->success(logVerboseCallback($log, true, 'DEBUG'));
 * $client->error(logVerboseCallback($log, false, 'ERROR'));.
 *
 * @param mixed $success
 * @param mixed $level
 */
function logVerboseCallback(callable $log, $success = false, $level = 'DEBUG')
{
    return function ($client) use ($log, $success, $level) {
        $url = strstr($client->getUrl(), '?', true);
        $content = sprintf(
            '* cURL site_uri: %s ip_addr: %s total_time: %s',
            $url,
            $client->getInfo(CURLINFO_PRIMARY_IP),
            $client->getInfo(CURLINFO_TOTAL_TIME)
        )."\n";
        if ($success) {
            $request = $client->getInfo(CURLINFO_HEADER_OUT);
            $content .= '> '.rtrim(str_replace("\r\n", "\n> ", $request), '> ');
        } else {
            $method = $client->getOpt(CURLOPT_CUSTOMREQUEST);
            $content .= '> '.$method.' '.$client->getUrl()."\n";
            $request = implode("\r\n", $client->getRequestHeaders());
            $content .= '> '.rtrim(str_replace("\r\n", "\n> ", $request), '> ')."\n";
        }
        $content .= $client->getOpt(CURLOPT_POSTFIELDS)."\n";
        $response = $client->getRawResponseHeaders();
        $content .= '< '.rtrim(str_replace("\r\n", "\n< ", $response), '< ');
        $content .= $success ? $client->getRawResponse() : 'ERROR: '.$client->getErrorMessage();
        \call_user_func($log, $level, $content);
    };
}
