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
$loader->class(__DIR__ . '/MY_Timer.php', 'MY_Timer');
$loader->name_space('Psr\\Log', VNDPATH . 'psr/log/Psr/Log');
$loader->name_space('Curl', VNDPATH . 'php-curl-class/php-curl-class/src');

// 域名解析失败错误
const ERRNO_RESOLVE_FAIL = 6;
const ERRNO_CONN_FAIL = 7;
const ERRNO_DNS_FAIL = 28;

/**
 * cURL的HTTP客户端
 */
class MY_Curl
{
    use \Psr\Log\LoggerAwareTrait;

    public $client;
    public $timer;
    public $is_multi = false;
    public $batch_data = [];

    public function __construct(array $config = [])
    {
        $config += [
            'base_url' => '', 'user_agent' => 'Mozilla/4.0',
            'is_multi' => false, 'concurrency' => 0, 'gap_secs' => 0,
        ];
        $this->is_multi = !empty($config['is_multi']);
        $this->client = createClient($this, $config['base_url'], $this->is_multi);
        if ($config['user_agent']) {
            $this->client->setUserAgent($config['user_agent']);
        }
        if ($this->is_multi && $config['concurrency'] > 0) {
            $this->client->setConcurrency($config['concurrency']);
        }
        if ($config['gap_secs'] > 0) { // 每几秒发送一次累积的消息
            $this->timer = new MY_Timer($config);
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * 关闭前将剩余消息发出
     */
    public function close()
    {
        if (!$this->is_multi || is_null($this->timer) || empty($this->batch_data)) {
            return false;
        }
        $this->batchSend(array_splice($this->batch_data, 0));
        return true;
    }

    /**
     * 发送消息
     */
    public function send(array $msgdata, $ctype = '')
    {
        if ($ctype) {
            $this->setContentType($ctype);
        }
        if ($this->is_multi) {
            $this->client->addPost($msgdata);
            $this->client->start();
        } else {
            $this->client->post($msgdata);
            $result = $this->client->getResponse();
            $this->client->close();
            return $result;
        }
    }

    /**
     * 批量发送消息
     */
    public function batchSend(array $batch_data, $ctype = '')
    {
        if (empty($batch_data)) {
            return 0;
        }
        if ($ctype) {
            $this->setContentType($ctype);
        }
        if ($this->is_multi) {
            foreach ($batch_data as $msgdata) {
                $this->client->addPost($msgdata);
            }
            $this->client->start();
        } else {
            foreach ($batch_data as $msgdata) {
                $this->client->post($msgdata);
            }
            $this->client->close();
        }
    }

    /**
     * 累积一定数量的消息一起发送
     */
    public function append(array $msgdata)
    {
        if (!$this->is_multi || is_null($this->timer)) {
            return false;
        }
        $this->batch_data[] = $msgdata;
        if ($this->timer->isHitting()) {
            $this->batchSend(array_splice($this->batch_data, 0));
            return true;
        }
        return false;
    }

    public function addCookie($name, $value)
    {
        $this->client->setCookie($name, $value);
    }

    public function setCookieFile($filename)
    {
        if (file_exists($filename)) {
            $this->client->setCookieFile($filename);
            $this->client->setCookieJar($filename);
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
        $this->client->setHeader('Content-Type', $ctype);
    }

    /**
     * 最后一次调用是否成功
     */
    public function isSuccess()
    {
        if (!$this->is_multi) {
            $code = $this->client->getHttpStatusCode();
            return $code >= 100 && $code < 400;
        }
    }

    /**
     * 记录日志的方法
     */
    public function getLogFunc()
    {
        if (isset($this->logger)) {
            return [$this->logger, 'log'];
        } elseif (function_exists('log_message')) {
            return'log_message';
        }
    }
}

/**
 * 创建cURL的HTTP客户端
 * NOTICE:
 *   PHP的cURL无法看到本地的/etc/hosts文件，而Bash的curl可以
 */
function createClient($wrapper = null, $base_url = null, $is_multi = false)
{
    if ($is_multi) {
        $client = new Curl\MultiCurl($base_url);
    } else {
        $client = new Curl\Curl($base_url);
    }
    $client->setRetry(replaceHostCallback(false, 443));
    if ($wrapper && $log = $wrapper->getLogFunc()) {
        $client->success(logSimpleCallback($log, true, 'DEBUG'));
        $client->error(logSimpleCallback($log, false, 'ERROR'));
    }
    return $client;
}


/**
 * 是否网络不可达（如域名解析失败）的错误
 */
function isErrorUnreachable($code)
{
    static $failures = [
        ERRNO_RESOLVE_FAIL,
        ERRNO_DNS_FAIL,
        ERRNO_CONN_FAIL,
    ];
    return in_array($code, $failures);
}

/**
 * 解析域名对应的IP地址
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
 * 读取临时文件内容
 */
function readTempFile($temp)
{
    if (!is_resource($temp)) {
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
 *  $client->setRetry(replaceHostCallback());
 */
function replaceHostCallback($always = false, $resolve_port = 0, array $hosts = [])
{
    return function ($client) use ($always, $resolve_port, $hosts) {
        if (!$always && !isErrorUnreachable($client->getCurlErrorCode())) {
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
        if ($resolve_port > 0 && version_compare(PHP_VERSION, '5.5.0') >= 0) {
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
 * $client->error(logSimpleCallback($log, false, 'ERROR'));
 */
function logSimpleCallback(callable $log, $success = false, $level = 'DEBUG')
{
    return function ($client) use ($log, $success, $level) {
        $content = sprintf(
            'REST> ip_addr: %s total_time: %s',
            $client->getInfo(CURLINFO_PRIMARY_IP),
            $client->getInfo(CURLINFO_TOTAL_TIME)
        ) . "\n";
        $method = $client->getOpt(CURLOPT_CUSTOMREQUEST);
        if (empty($method)) {
            $method = strstr($client->getInfo(CURLINFO_HEADER_OUT), ' ', true);
        }
        $content .= 'REST> ' . $method . ' ' . $client->getUrl() . "\n";
        $request = $client->getOpt(CURLOPT_POSTFIELDS);
        $content .= 'REST> ' . $request . "\n";
        $response = $success ? $client->getRawResponse() : 'ERROR: ' . $client->getErrorMessage();
        $content .= 'REST> ' . $response . "\n";
        call_user_func($log, $level, $content);
    };
}

/**
 * 记录详细日志
 * $client->success(logVerboseCallback($log, true, 'DEBUG'));
 * $client->error(logVerboseCallback($log, false, 'ERROR'));
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
        ) . "\n";
        if ($success) {
            $request = $client->getInfo(CURLINFO_HEADER_OUT);
            $content .= '> ' . rtrim(str_replace("\r\n", "\n> ", $request), '> ');
        } else {
            $method = $client->getOpt(CURLOPT_CUSTOMREQUEST);
            $content .= '> ' . $method . ' ' . $client->getUrl() . "\n";
            $request = implode("\r\n", $client->getRequestHeaders());
            $content .= '> ' . rtrim(str_replace("\r\n", "\n> ", $request), '> ') . "\n";
        }
        $content .= $client->getOpt(CURLOPT_POSTFIELDS) . "\n";
        $response = $client->getRawResponseHeaders();
        $content .= '< ' . rtrim(str_replace("\r\n", "\n< ", $response), '< ');
        $content .= $success ? $client->getRawResponse() : 'ERROR: ' . $client->getErrorMessage();
        call_user_func($log, $level, $content);
    };
}
