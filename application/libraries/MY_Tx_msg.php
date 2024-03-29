<?php

/**
 * 腾讯云消息.
 */
class MY_Tx_msg
{
    //等于 JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    public const JSON_OPTS = 320; //5.4及以下不允许常量使用表达式
    public const OPT_IS_SYS = 1;
    public const OPT_IS_SYNC = 2;

    public $text = '';
    public $life_time = 604800;

    public function __construct($text = '', $life_time = 0)
    {
        $this->create($text, $life_time);
    }

    /**
     * 检查是否包含选项.
     *
     * @param mixed $opt
     * @param mixed $val
     */
    public static function hasOpt($opt, $val)
    {
        return ($opt & $val) === $val;
    }

    /**
     * 将内容字符串中的变量替换掉.
     *
     * @param mixed $content
     * @param mixed $prefix
     * @param mixed $subfix
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
                $replacers[$prefix.$key.$subfix] = $value;
            }
        }

        return strtr($content, $replacers);
    }

    /**
     * 创建随机整数.
     *
     * @param mixed $size
     */
    public static function createRandNum($size = 9)
    {
        $num = mt_rand(100000000, 999999999);

        return ($size > 0 && $size < 9) ? substr($num, 0, $size) : $num;
    }

    /**
     * APNs推送配置.
     *
     * @param mixed $title
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
     * 推送内容.
     *
     * @param mixed $text
     * @param mixed $extjson
     * @param mixed $apns_title
     */
    public static function createPushInfo($text, $extjson, $apns_title = '')
    {
        $data = [
            'PushFlag' => 0,
            'Desc' => trim($text),   // 展示在标题栏目中的描述
            'Ext' => $extjson,   // 这是透传的内容
        ];
        if ($apns_title) {
            $data['ApnsInfo'] = self::createApnsInfo($apns_title);
        }

        return $data;
    }

    /**
     * 系统消息项.
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
     * 自定义消息项.
     *
     * @param mixed $text
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
     * 自定义消息项.
     *
     * @param mixed $type
     * @param mixed $data
     */
    public static function createCustomTypeItem($type, $data)
    {
        $inner = [
            'type' => trim($type),
            'data' => json_encode($data, self::JSON_OPTS),
            'id' => uniqid('w_'),
        ];

        return [
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => [
                'Data' => json_encode($inner, self::JSON_OPTS),
                'Desc' => '',
                'Sound' => 'dingdong.aiff',
            ],
        ];
    }

    /**
     * 文本消息项.
     *
     * @param mixed $text
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
     * 修改文本消息内容.
     *
     * @param mixed $text
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
     * 组装消息.
     *
     * @param mixed $item
     * @param mixed $opt
     */
    public function build($item, array $params = [], $opt = 0, array $extra = [])
    {
        $body = null === $item ? [] : [$item];
        if (self::hasOpt($opt, self::OPT_IS_SYS)) { // 系统消息
            $body[] = self::createSysItem($extra);
        }
        $data = [
            'MsgRandom' => (int) (self::createRandNum() + 1e9),
            'MsgTimeStamp' => time(),
            'MsgBody' => $body,
        ];
        if (self::hasOpt($opt, self::OPT_IS_SYNC)) { // 系统消息
            $data['SyncOtherMachine'] = 2;
        }

        return array_merge($params, $data);
    }

    /**
     * 文本消息.
     *
     * @param mixed $from
     * @param mixed $to
     * @param mixed $is_system
     */
    public function buildText($from, $to = 0, $is_system = false, array $extra = [])
    {
        if (empty($this->text) || empty($from)) {
            return;
        }
        $text_elem = self::createTextItem($this->text);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];
        $opt = self::OPT_IS_SYNC | ($is_system ? self::OPT_IS_SYS : 0);

        return $this->build($text_elem, $params, $opt, $extra);
    }

    /**
     * 提醒消息.
     *
     * @param mixed $from
     * @param mixed $to
     * @param mixed $is_system
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
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];
        $opt = self::OPT_IS_SYNC | ($is_system ? self::OPT_IS_SYS : 0);

        return $this->build($cust_elem, $params, $opt, $extra);
    }

    /**
     * 自定义类型消息.
     *
     * @param mixed $from
     * @param mixed $to
     * @param mixed $type
     * @param mixed $data
     */
    public function buildCustomData($from, $to, $type, $data)
    {
        if (empty($from) || empty($to)) {
            return;
        }
        $cust_elem = self::createCustomTypeItem($type, $data);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];

        return $this->build($cust_elem, $params, self::OPT_IS_SYNC);
    }

    /**
     * 推送消息.
     *
     * @param mixed $from
     * @param mixed $to
     * @param mixed $is_system
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
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
            'OfflinePushInfo' => self::createPushInfo($this->text, $extjson, '标题'),
        ];
        $opt = self::OPT_IS_SYNC | ($is_system ? self::OPT_IS_SYS : 0);

        return $this->build($cust_elem, $params, $opt, $extra);
    }

    /**
     * 全员推送
     *
     * @param mixed $from
     * @param mixed $content
     * @param mixed $type
     */
    public function buildAllMemberPush($from, $content, $type = 'bullet_chat')
    {
        if (empty($from)) {
            return;
        }
        $cust_elem = self::createCustomTypeItem($type, ['content' => $content]);
        $push_info = self::createPushInfo('', '');
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
        ];
        if (! empty($to)) {
            $params['To_Account'] = (string) $to;
        }
        $params['OfflinePushInfo'] = $push_info;

        return $this->build($cust_elem, $params);
    }

    /**
     * 撤回消息.
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function buildRevoke($from, $to, array $orig = [])
    {
        if (empty($from) || empty($to)) {
            return;
        }
        $data = [
            'Type' => '1', //撤回类型0或空用户撤回1系统撤回
            'RevokeDesc' => '系统撤回了一条消息', //撤回消息描述
            'Targets' => (string) $from, //对方用户id
            'MsgRandom' => isset($orig['msgid']) ? (int) ($orig['msgid']) : 0, //唯一标识哪路通话
            'Timestamp' => isset($orig['msgtime']) ? (int) ($orig['msgtime']) : 0,
            'MsgSeq' => isset($orig['msgseq']) ? (int) ($orig['msgseq']) : 0,
            'Ext' => 'RevokeMsg', //撤回消息命令
        ];
        $content = ['Data' => json_encode($data, self::JSON_OPTS)];
        $cust_elem = self::createCustomDescItem('', $content);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];

        return $this->build($cust_elem, $params, self::OPT_IS_SYNC);
    }

    /**
     * 挂断信号.
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function buildHangUp($from, $to, array $call = [])
    {
        if (empty($from) || empty($to)) {
            return;
        }
        $data = [
            'Sender' => (string) $from, //挂断方
            'Targets' => [(string) $to],
            'AVRoomID' => isset($call['callid']) ? $call['callid'] : '', //唯一标识哪路通话
            'UserAction' => 134, //134挂断
            'CallDate' => time(), //呼叫时间戳
            'CallSponsor' => (string) $from,
            'CallType' => isset($call['isvideo']) && '0' === $call['isvideo'] ? 1 : 2, //1语音2视频
            'reason' => isset($call['reason']) ? $call['reason'] : '',
        ];
        $content = ['Ext' => 'CallNotification', 'Data' => json_encode($data, self::JSON_OPTS)];
        $cust_elem = self::createCustomDescItem('', $content);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];

        return $this->build($cust_elem, $params, self::OPT_IS_SYNC);
    }

    /**
     * 弹框信息.
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function buildPopUp($from, $to = 0, array $buttons = [])
    {
        if (empty($this->text) || empty($from)) {
            return;
        }
        $nickname = $headphoto = '';
        if (is_array($from)) { // 同时传userid和nickname
            $nickname = isset($from['nickname']) ? $from['nickname'] : '';
            if (isset($from['headphoto']) && $from['headphoto']) {
                $headphoto = $from['headphoto'];
            } elseif (isset($from['smallheadpho']) && $from['smallheadpho']) {
                $headphoto = $from['smallheadpho'];
            } elseif (isset($from['midleheadpho']) && $from['midleheadpho']) {
                $headphoto = $from['midleheadpho'];
            }
            $from = $from['userid'];
        }
        $data = [
            'id' => uniqid('w_'), 'text' => trim($this->text),
            'headphoto' => $headphoto, 'botton' => $buttons,
            'nickname' => $nickname, 'high' => 60, 'Ext' => 'common_msg',
        ];
        $content = ['Sound' => 'dingdong.aiff', 'Data' => json_encode($data, self::JSON_OPTS)];
        $cust_elem = self::createCustomDescItem($this->text, $content);
        $params = [
            'MsgLifeTime' => $this->life_time,
            'From_Account' => (string) $from,
            'To_Account' => (string) $to,
        ];

        return $this->build($cust_elem, $params, self::OPT_IS_SYNC);
    }
}
