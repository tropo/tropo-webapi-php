<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class RedirectTest extends TestCase
{
	public function testRedirect()
	{
		$tropo = new Tropo();
		$params = array(
			'name' => 'foo',
			'required' => true
			);

		$tropo->redirect("sip:pengxli@192.168.26.1:5678", $params);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"redirect":{"to":"sip:pengxli@192.168.26.1:5678","name":"foo","required":true}}]}');
	}

	public function testRedirect1()
	{
		$tropo = new Tropo();
		$redirect = new Redirect("sip:pengxli@192.168.26.1:5678", null, "foo", true);
		$tropo->redirect($redirect);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"redirect":{"to":"sip:pengxli@192.168.26.1:5678","name":"foo","required":true}}]}');
	}

}
?>