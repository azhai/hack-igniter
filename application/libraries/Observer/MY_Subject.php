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

namespace Mylib\Observer;

use InvalidArgumentException;
use SplObjectStorage;
use SplObserver;
use SplSubject;

/**
 * 被观察对象，实现了观察者模式.
 */
class MY_Subject implements SplSubject
{
    public const TYPE_NOTIFY_ALL = 0;
    public const TYPE_NOTIFY_ONCE = 1;

    protected $_observers;
    protected $_signals = [];

    public function __call($name, $args)
    {
        if (isset($this->_signals[$name])) {
            $type = $this->_signals[$name];

            return $this->notify($name, $args, $type);
        }
    }

    public function add_signal($name, $type = 0)
    {
        $this->_signals[$name] = $type;
    }

    /**
     * @return mixed
     */
    public function attach(SplObserver $observer)
    {
        $this->observers()->attach($observer);
    }

    /**
     * @return SplObjectStorage
     */
    public function observers()
    {
        if (null === $this->_observers) {
            $this->_observers = new SplObjectStorage();
        }

        return $this->_observers;
    }

    /**
     * @return mixed
     */
    public function detach(SplObserver $observer)
    {
        $this->observers()->detach($observer);
    }

    /**
     * @return mixed
     */
    public function notify()
    {
        //检查参数
        $num_args = \func_num_args();
        if (0 === $num_args) {
            return;
        }
        $name = func_get_arg(0);
        $args = ($num_args > 1) ? func_get_arg(1) : [];
        $notify_type = self::TYPE_NOTIFY_ALL;
        if ($num_args > 2) {
            $notify_type = (int) (func_get_arg(2));
        }
        if (! \is_array($args)) {
            throw new InvalidArgumentException();
        }

        return $this->_notify($name, $args, (int) $notify_type);
    }

    /**
     * 通知Observer.
     *
     * @param string $name        方法名
     * @param array  $args        参数组，不含Subject
     * @param int    $notify_type
     */
    protected function _notify($name, array $args, $notify_type = 0)
    {
        //依次通知
        $observers = $this->observers();
        if (0 === $observers->count()) {
            return;
        }
        $notify_once = (self::TYPE_NOTIFY_ONCE === $notify_type);
        foreach ($observers as $observer) {
            $result = $observer->update($this, $name, $args);
            if ($notify_once && $result) {
                break;
            }
        }

        return $result;
    }
}
