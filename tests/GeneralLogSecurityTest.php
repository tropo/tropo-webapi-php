<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';

class GeneralLogSecurityTest extends TestCase
{
	public function testGeneralLogSecurity()
	{
		$tropo = new Tropo();
		$tropo->generalLogSecurity("suppress");
		$say = new Say("this is not logged.", null, null, null, null, "nolog");
		$tropo->say($say);
		$tropo->generalLogSecurity("none");
		$say = new Say("this will be logged.", null, null, null, null, "log");
		$tropo->say($say);
		$this->assertEquals(sprintf($tropo), '{"tropo":[{"generalLogSecurity":"suppress"},{"say":[{"value":"this is not logged.","name":"nolog"}]},{"generalLogSecurity":"none"},{"say":[{"value":"this will be logged.","name":"log"}]}]}');
	}

}
?>