<?php
/**
 * @author bigbigant
 * get PID by PID manager
 */

require __DIR__ . '/bootstrap.inc.php';

use Comos\Qpm\Pid\Manager;
$man = new Manager(__DIR__ . '/pid_main.php.pid');
echo $man->getProcess()->getPid()."\n";
