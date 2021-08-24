<?php

/**
 * 腾讯云消息
 */
class TxMsg
{
    //等于 JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    const JSON_OPTS = 320; //5.4及以下不允许常量使用表达式

    public $text = '';
    public $life_time = 604800;

    public function __construct($text = '', $life_time = 0)
    {
        $this->create($text, $life_time);
    }

    /**
     * 将内容字符串中的变量替换掉.
     */
    public static function replaceWith($content, array $context = [], $prefix = '{{', $subfix = '}}')
    {
        if (empty($context)) {
            return $content;
        }
        if (empty($prefix) && empty($subfix)) {
            $replacers = $context;
        } else {
            $replacers = [];
            foreach ($context as $key => $value) {
                $replacers[$prefix . $key . $subfix] = $value;
            }
        }
        $content = strtr($content, $replacers);
        return $content;
    }

    /**
     * 创建随机整数
     */
    public static function createRandNum($size = 9)
    {
        $num = mt_rand(100000000, 999999999);
        return ($size > 0 && $size < 9) ? substr($num, 0, $size) : $num;
    }

    /**
     * APNs推送配置
     */
    public static function createApnsInfo($title = '标题')
    {
        return [
            'BadgeMode' => 1, // 1本条消息不需要计数 0要计数
            'Title' => $title,
            'SubTitle' => 'SubTitle名称',
        ];
    }

    /**
     * 推送内容
     */
    public static function createPushInfo($text, $extjson)
    {
        return [
            'PushFlag' => 0,
            'Desc' => trim($text),   // 展示在标题栏目中的描述
            'Ext' => $extjson,   // 这是透传的内容
            'ApnsInfo' => self::createApnsInfo(),
        ];
    }

    /**
     * 系统消息项
     */
    public static function createSysItem(array $extra = [])
    {
        $extra['MsgOper'] = 1;
        return [
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => [
                'Data' => json_encode($extra, self::JSON_OPTS),
            ],
        ];
    }

    /**
     * 自定义消息项
     */
    public static function createCustomDescItem($text, array $content = [])
    {
        $content['Desc'] = trim($text);
        return [
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => $content,
        ];
    }

    /**
     * 自定义消息项
     */
    public static function createCustomTypeItem($type, $data)
    {
        $inner = [
            'type' => trim($type),
            'data' => json_encode($data, self::JSON_OPTS),
        ];
        return [
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => [
                'Data' => json_encode($inner, self::JSON_OPTS),
            ],
        ];
    }

    /**
     * 文本消息项
     */
    public static function createTextItem($text)
    {
        return [
            'MsgType' => 'TIMTextElem',
            'MsgContent' => [
                'Text' => trim($text),
            ],
        ];
    }

    /**
     * 修改文本消息内容
     */
    public static function setDataText(array &$data, $text)
    {
        $data['MsgBody'][0]['MsgContent']['Text'] = $text;
    }

    public function create($text, $life_time = 0)
    {
        $this->text = $text;
        if ($life_time > 0) {
            $this->life_time = $life_time;
        }
    }

    /**
     * 组装消息
     */
    public function build($item, array $params = [], $is_system = false, array $extra = [])
    {
        $body = is_null($item) ? [] : [$item];
        if ($is_system) { // 系统消息
            $body[] = self::createSysItem($extra);
        }
        $data = array_merge($params, [
            'SyncOtherMachine' => 2,
            'MsgRandom' => intval(self::createRandNum() + 1e9),
            'MsgTimeStamp' => time(),
            'MsgBody' => $body,
        ]);
        return $data;
    }

    /**
     * 文本消息
     */
    public function buildText($from, $to = 0, $is_system = false, array $extra = [])
    {
        if (empty($this->text) || empty($from)) {
            return;
        }
        $text_elem = self::createTextItem($this->text);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string)$from,
            'To_Account' => (string)$to,
        ];
        return $this->build($text_elem, $params, $is_system, $extra);
    }

    /**
     * 提醒消息
     */
    public function buildTips($from, $to = 0, $is_system = false, array $extra = [])
    {
        if (empty($this->text) || empty($from)) {
            return;
        }
        $nickname = '';
        if (is_array($from)) { // 同时传userid和nickname
            $nickname = isset($from['nickname']) ? $from['nickname'] : '';
            $from = $from['userid'];
        }
        $data = [
            'id' => uniqid('w_'), 'text' => trim($this->text),
            'nickname' => $nickname, 'Ext' => 'tips',
        ];
        $content = ['Sound' => 'dingdong.aiff', 'Data' => json_encode($data, self::JSON_OPTS)];
        $cust_elem = self::createCustomDescItem($this->text, $content);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string)$from,
            'To_Account' => (string)$to,
        ];
        return $this->build($cust_elem, $params, $is_system, $extra);
    }

    /**
     * 自定义类型消息
     */
    public function buildCustomData($from, $to, $type, $data)
    {
        if (empty($from) || empty($to)) {
            return;
        }
        $cust_elem = self::createCustomTypeItem($type, $data);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string)$from,
            'To_Account' => (string)$to,
        ];
        return $this->build($cust_elem, $params);
    }

    /**
     * 推送消息
     */
    public function buildPushInfo($from, $to = 0, $is_system = false, array $extra = [])
    {
        if (empty($this->text) || empty($from)) {
            return;
        }
        $extjson = json_encode([
            'pushType' => 1,
            'data' => ['tartgetId' => $to],
        ], self::JSON_OPTS);
        $content = ['Sound' => 'dingdong.aiff', 'Ext' => $extjson, 'Data' => ''];
        $cust_elem = self::createCustomDescItem($this->text, $content);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string)$from,
            'To_Account' => (string)$to,
            'OfflinePushInfo' => self::createPushInfo($this->text, $extjson),
        ];
        return $this->build($cust_elem, $params, $is_system, $extra);
    }
}
