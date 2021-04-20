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

namespace Mylib\Util;

$loader = load_class('Loader', 'core');
$loader->name_space('Evenement', VENDPATH . 'evenement/evenement/src/Evenement');

/**
 * 事件调度
 */
class Event extends \Evenement\EventEmitter
{
}
