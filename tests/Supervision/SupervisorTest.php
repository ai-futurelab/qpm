<?php
/**
 * @author bigbigant
 */

namespace Comos\Qpm\Supervision;

use Comos\Qpm\Supervision\Supervisor;
use Comos\Qpm\Process\Process;

class SupervisorTest extends \PHPUnit_Framework_TestCase {
	protected function setUp() {
		parent::setUp();
		$this->_logFile = __FILE__.'.log';
		$this->_logFile1 = __FILE__.'.log1';
		@\unlink($this->_logFile);
		@\unlink($this->_logFile1);
	}
	
	protected function tearDown() {
	    @\unlink($this->_logFile);
	    @\unlink($this->_logFile1);
	}
	/**
	 *@expectedException InvalidArgumentException
	 */
	public function testOneForOne_InvalidArgument() {
		Supervisor::oneForOne(array());
	}
	/**
	 *@dataProvider dataProvider4testMultiGroupOneForOne_InvalidArgument
	 *@expectedException \InvalidArgumentException
	 */
	public function testMultiGroupOneForOne_InvalidArgument($data) {
		Supervisor::multiGroupOneForOne($data);
	}
	public function dataProvider4testMultiGroupOneForOne_InvalidArgument() {
		return array(
			array(array()),
			array(array(array(),array())),
			array(array('x')),
			array('x'),
		);
	}
	public function testMultiGroupOneForOne_CreateKeeper() {
		Supervisor::multiGroupOneForOne(array(
			array('worker' => function() {exit;}),
			array('worker' => function() {exit;}),
			array('worker' => function() {exit;},'quantity' => 3, 'maxRestartTimes' => 3),
		));
	}
	
	public function testMultiGroupOneForOne() {
		$cmd = sprintf("%s %s %s %s",
			PHP_BINDIR.'/php',
			\preg_replace('/\\.php$/', '.cmd.php', __FILE__),
			escapeshellarg($this->_logFile),
			escapeshellarg($this->_logFile1)
		);
		exec($cmd);
		$this->assertEquals(1, preg_match('/^1{4,10}$/', \file_get_contents($this->_logFile)), \file_get_contents($this->_logFile));
		$this->assertEquals(1, preg_match('/^2{15,25}/', \file_get_contents($this->_logFile1)), \file_get_contents($this->_logFile1));
	}
}
