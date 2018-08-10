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

    public function testSayWithOptions1() {
      $tropo = new Tropo();
      $tropo->say("Please enter your account number...",array('name' => 'say','media' => 'http://user:pass@server.com/1.jpg'));
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","name":"say","media":"http://user:pass@server.com/1.jpg"}]}]}');
    }

    public function testSayWithOptions2() {
      $tropo = new Tropo();
      $tropo->say("Please enter your account number...",array('name' => 'say','media' => array('http://server.com/1.jpg', 'this is a inline text content', 'http://filehosting.tropo.com/account/1/2.text')));
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","name":"say","media":["http://server.com/1.jpg","this is a inline text content","http://filehosting.tropo.com/account/1/2.text"]}]}]}');
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

    public function testCreateSayObject2()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$say = new Say("Please enter your account number...", SayAs::$date, null, Voice::$US_English_female_allison, $allowSignals, null, true, "suppress", "http://user:pass@server.com/1.jpg");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress","media":"http://user:pass@server.com/1.jpg"}]}]}');
	}

    public function testCreateSayObject3()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$say = new Say("Please enter your account number...", SayAs::$date, null, Voice::$US_English_female_allison, $allowSignals, null, true, "suppress", array('http://server.com/1.jpg', 'this is a inline text content', 'http://filehosting.tropo.com/account/1/2.text'));
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress","media":["http://server.com/1.jpg","this is a inline text content","http://filehosting.tropo.com/account/1/2.text"]}]}]}');
	}

    public function testCreateSayObject4()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$params = array(
			"as"=>SayAs::$date,
			"event"=>"event",
			"voice"=>Voice::$US_English_female_allison,
			"allowSignals"=>$allowSignals,
			"promptLogSecurity"=>"suppress",
			"required"=>true,
			"media"=>"http://user:pass@server.com/1.jpg");
		$tropo->say("Please enter your account number...",$params);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress","media":"http://user:pass@server.com/1.jpg"}]}]}');
	}

	public function testCreateSayObject5()
	{
		$tropo = new Tropo();
		$allowSignals = array('exit','quit');
		$params = array(
			"as"=>SayAs::$date,
			"event"=>"event",
			"voice"=>Voice::$US_English_female_allison,
			"allowSignals"=>$allowSignals,
			"promptLogSecurity"=>"suppress",
			"required"=>true,
			"media"=>array('http://server.com/1.jpg', 'this is a inline text content', 'http://filehosting.tropo.com/account/1/2.text'));
		$tropo->say("Please enter your account number...",$params);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"say":[{"value":"Please enter your account number...","as":"DATE","voice":"allison","allowSignals":["exit","quit"],"required":true,"promptLogSecurity":"suppress","media":["http://server.com/1.jpg","this is a inline text content","http://filehosting.tropo.com/account/1/2.text"]}]}]}');
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