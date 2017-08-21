<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class SayTest extends TestCase
{
	public function testCreateSayObject()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$say = new Say("Please enter your account number...", SayAs::$date, null, Voice::$US_English_female_allison, $allowSignals, "say", true, "suppress");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"name":"say","required":true,"promptLogSecurity":"suppress"}]}]}');
	}

	public function testCreateSayObject1()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$params = array(
			"name"=>"say",
			"as"=>SayAs::$date,
			"event"=>"event",
			"voice"=>Voice::$US_English_female_allison,
			"allowSignals"=>$allowSignals,
			"promptLogSecurity"=>"suppress",
			"required"=>true);
		$tropo->say("Please enter your account number...",$params);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"name":"say","required":true,"promptLogSecurity":"suppress"}]}]}');
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

	public function testFailsSayWithNoNameParameter()
	{
		$tropo = new Tropo();
		try{
			$say = new Say("Please enter your account number...");
			$tropo->say($say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'name'");
		}
	}

	public function testFailsSayWithNoNameParameter1()
	{
		$tropo = new Tropo();
		try{
			$say = new Say("Please enter your account number...", null, null, null, null, null, null, null);
			$tropo->say($say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'name'");
		}
	}

	public function testFailsSayWithNoNameParameter2()
	{
		$tropo = new Tropo();
		try{
			$say = new Say("Please enter your account number...", null, null, null, null, "", null, null);
			$tropo->say($say);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Missing required property: 'name'");
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

	public function testFailsSayWithNoNameParameter3()
	{
		$tropo = new Tropo();
		try{
			$tropo->say("Please enter your account number...");
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "When Argument 1 passed to Tropo::say() is a string, argument 2 passed to Tropo::say() must be of the type array.");
		}
	}

	public function testFailsSayWithNoNameParameter4()
	{
		$tropo = new Tropo();
		$params = array("name"=>null);
		try{
			$tropo->say("Please enter your account number...",$params);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Required property: 'name' must be a string.");
		}
	}

	public function testFailsSayWithNoNameParameter5()
	{
		$tropo = new Tropo();
		$params = array("name"=>"");
		try{
			$tropo->say("Please enter your account number...",$params);
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), "Required property: 'name' must be a string.");
		}
	}
}
?>