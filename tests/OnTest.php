<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class OnTest extends TestCase{

	public function testCreateOnObject() {

		$tropo = new Tropo();
		$say = new Say("object continue!", null, null, null, null, null, null, null);
		$on = new On("continue", "say.php", $say);
		$tropo->on($on);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"on":[{"event":"continue","next":"say.php","say":{"value":"object continue!"}}]}]}');
	}

	public function testCreateOnObject1() {

		$tropo = new Tropo();
		$say = new Say("array continue!", null, null, null, null, null, null, null);
		$on = array(
			'event' => 'continue',
			'next' => 'say.php',
			'say' => $say
			);
		$tropo->on($on);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"on":[{"event":"continue","next":"say.php","say":{"value":"array continue!"}}]}]}');
	}

	public function testFailsOnWithNoEventParameter() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!", null, null, null, null, null, null, null);
			@ $on = new On();
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter1() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!", null, null, null, null, null, null, null);
			@ $on = new On(null, "say.php", $say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter2() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!", null, null, null, null, null, null, null);
			@ $on = new On("", "say.php", $say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter3() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!", null, null, null, null, null, null, null);
			$say = new Say("array continue!", null, null, null, null, null, null, null);
			@ $on = array(
				'next' => 'say.php',
				'say' => $say
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter4() {
		$tropo = new Tropo();
		try {
			$say = new Say("array continue!", null, null, null, null, null, null, null);
			$on = array(
				'event' => null,
				'next' => 'say.php',
				'say' => $say
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter5() {
		$tropo = new Tropo();
		try {
			$say = new Say("array continue!", null, null, null, null, null, null, null);
			$on = array(
				'event' => '',
				'next' => 'say.php',
				'say' => $say
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoSayParameter() {
		$tropo = new Tropo();
		try {
			$on = new On("continue", "say.php");
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'say'");
		}
	}

	public function testFailsOnWithNoSayParameter1() {
		$tropo = new Tropo();
		try {
			$on = array(
				'event' => 'continue',
				'next' => 'say.php'
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'say'");
		}
	}

	public function testFailsOnWithNoSayParameter2() {
		$tropo = new Tropo();
		try {
			$on = array(
				'event' => 'continue',
				'next' => 'say.php',
				'say' => null
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'say'");
		}
	}
}
?>