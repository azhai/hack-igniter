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

$loader = load_class('Loader', 'core');
$loader->name_space('Evenement', VENDPATH.'evenement/evenement/src/Evenement');

/**
 * 事件调度.
 */
class Event extends \Evenement\EventEmitter
{
    public const SLOT_PREFIX = 'slot_';
    public $event_names = [];

    /**
     * 注册执行者的公开方法为事件.
     *
     * @param mixed $worker
     * @param mixed $dir
     */
    public function addWorker($worker, $dir = '')
    {
        $dir = empty($dir) ? '' : trim($dir, '/');
        if ($dir && ! isset($this->event_names[$dir])) {
            $this->event_names[$dir] = [];
        }
        $prelen = \strlen(self::SLOT_PREFIX);
        $methods = get_class_methods($worker);
        foreach ($methods as $name) {
            $name = strtolower($name);
            if (! starts_with($name, self::SLOT_PREFIX)) {
                continue;
            }
            $event_name = trim(substr($name, $prelen), '_');
            if ($dir) {
                $this->event_names[$dir][$event_name] = 1;
                $event_name = $dir.'/'.$event_name;
            } else {
                $this->event_names[$event_name] = 1;
            }
            $this->on($event_name, [$worker, $name]);
        }
    }
}
