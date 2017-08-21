<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class OnTest extends TestCase{

	public function testCreateOnObject() {

		$tropo = new Tropo();
		$say = new Say("object continue!");
		$on = new On(Event::$continue, "say.php", $say);
		$tropo->on($on);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"on":[{"event":"continue","next":"say.php","say":{"value":"object continue!"}}]}]}');
	}

	public function testCreateOnObject1() {

		$tropo = new Tropo();
		$say = new Say("array continue!");
		$on = array(
			'event' => Event::$continue,
			'next' => 'say.php',
			'say' => $say
			);
		$tropo->on($on);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"on":[{"event":"continue","next":"say.php","say":{"value":"array continue!"}}]}]}');
	}

	public function testFailsOnWithNoEventParameter1() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!");
			@ $on = new On(null, "say.php", $say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter2() {
		$tropo = new Tropo();
		try {
			$say = new Say("object continue!");
			@ $on = new On("", "say.php", $say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'event'");
		}
	}

	public function testFailsOnWithNoEventParameter3() {
		$tropo = new Tropo();
		try {
			$say = new Say("array continue!");
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
			$say = new Say("array continue!");
			$on = array(
				'event' => null,
				'next' => 'say.php',
				'say' => $say
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Required property: 'event' must be a string.");
		}
	}

	public function testFailsOnWithNoEventParameter5() {
		$tropo = new Tropo();
		try {
			$say = new Say("array continue!");
			$on = array(
				'event' => '',
				'next' => 'say.php',
				'say' => $say
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Required property: 'event' must be a string.");
		}
	}

	public function testFailsOnWithNoSayParameter2() {
		$tropo = new Tropo();
		try {
			$on = array(
				'event' => Event::$continue,
				'next' => 'say.php',
				'say' => null
				);
			$tropo->on($on);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Property: 'say' must be a Say of array or an instance of Say.");
		}
	}
}
?>