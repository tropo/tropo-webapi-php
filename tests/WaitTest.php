<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class WaitTest extends TestCase
{
	public function testWaitWithMinOption()
	{
		$tropo = new Tropo();
		$say = new Say("Connected!", null, null, null, null, "connected");
		$tropo->say($say);
		$wait = array(
			'milliseconds' => 8000
			);
		$tropo->wait($wait);
		$say = new Say("Bye!", null, null, null, null, "bye");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Connected!","name":"connected"}]},{"wait":{"milliseconds":8000}},{"say":[{"value":"Bye!","name":"bye"}]}]}');
	}

	public function testWaitWithAllOption()
	{
		$tropo = new Tropo();
		$say = new Say("Connected!", null, null, null, null, "connected");
		$tropo->say($say);
		$wait = array(
			'milliseconds' => 8000,
			'allowSignals' => array('exit','quit')
			);
		$tropo->wait($wait);
		$say = new Say("Bye!", null, null, null, null, "bye");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Connected!","name":"connected"}]},{"wait":{"milliseconds":8000,"allowSignals":["exit","quit"]}},{"say":[{"value":"Bye!","name":"bye"}]}]}');
	}

	public function testWaitObject()
	{
		$tropo = new Tropo();
		$say = new Say("Connected!", null, null, null, null, "connected");
		$tropo->say($say);
		$wait = new Wait(8000);
		$tropo->wait($wait);
		$say = new Say("Bye!", null, null, null, null, "bye");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Connected!","name":"connected"}]},{"wait":{"milliseconds":8000}},{"say":[{"value":"Bye!","name":"bye"}]}]}');
	}

	public function testWaitObject1()
	{
		$tropo = new Tropo();
		$say = new Say("Connected!", null, null, null, null, "connected");
		$tropo->say($say);
		$wait = new Wait(8000, array('exit','quit'));
		$tropo->wait($wait);
		$say = new Say("Bye!", null, null, null, null, "bye");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Connected!","name":"connected"}]},{"wait":{"milliseconds":8000,"allowSignals":["exit","quit"]}},{"say":[{"value":"Bye!","name":"bye"}]}]}');
	}

}
?>