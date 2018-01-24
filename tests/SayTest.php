<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class SayTest extends TestCase
{
	public function testSayWithMinOptions() {
      $tropo = new Tropo();
      $tropo->say("Please enter your account number...");
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number..."}]}]}');
    }

	public function testSayWithOptions() {
      $tropo = new Tropo();
      $tropo->say("Please enter your account number...",array('name' => 'say'));
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","name":"say"}]}]}');
    }

	public function testCreateSayObject()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$say = new Say("Please enter your account number...", SayAs::$date, null, Voice::$US_English_female_allison, $allowSignals, null, true, "suppress");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress"}]}]}');
	}

	public function testCreateSayObject1()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$params = array(
			"as"=>SayAs::$date,
			"event"=>"event",
			"voice"=>Voice::$US_English_female_allison,
			"allowSignals"=>$allowSignals,
			"promptLogSecurity"=>"suppress",
			"required"=>true);
		$tropo->say("Please enter your account number...",$params);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress"}]}]}');
	}

	public function testFailsSayWithNoValueParameter1()
	{
		try{
			@ $say = new Say(null);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'value'");
		}
	}

	public function testFailsSayWithNoValueParameter2()
	{
		try{
			@ $say = new Say("");
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'value'");
		}
	}

	public function testFailsSayWithNoValueParameter4()
	{
		$tropo = new Tropo();
		try{
			@ $tropo->say(null);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Argument 1 passed to Tropo::say() must be a string or an instance of Say.");
		}
	}

	public function testFailsSayWithNoValueParameter5()
	{
		$tropo = new Tropo();
		try{
			@ $tropo->say("");
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Argument 1 passed to Tropo::say() must be a string or an instance of Say.");
		}
	}
}
?>