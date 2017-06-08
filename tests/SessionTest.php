<?php
use PHPUnit\Framework\TestCase;
require_once '../tropo.class.php';

class OnTest extends TestCase{

	public function testCreateOnObject() {

		$strSession = '{"session":{"id":"35b00c154f2fecacba37fad74e64a7e2","accountId":"1","applicationId":"1","timestamp":"2017-06-08T03:40:19.283Z","userType":"HUMAN","initialText":null,"callId":"c5b298fc0785fda9029f7f3b5aeef7ab","to":{"id":"9992801029","e164Id":"9992801029","name":"9992801029","channel":"VOICE","network":"SIP"},"from":{"id":"pengxli","e164Id":"pengxli","name":"pengxli","channel":"VOICE","network":"SIP"},"headers":{"Call-ID":"83369NTAxNDI2NDA4MWMzYTBiNzBiNmM0ZTVlMTQ4NjRlNmY","CSeq":"1 INVITE","Max-Forwards":"69","Request URI":"sip:9992801029@10.140.254.38;x-rt=0","Record-Route":"<sip:192.168.26.111:5060;transport=udp;lr>","x-sid":"6f1e3b7b2ace2a7785780b6337641388","User-Agent":"X-Lite release 4.9.7.1 stamp 83369","From":"<sip:pengxli@10.140.254.38>;tag=1bb1ef33","Supported":"replaces","Allow":"SUBSCRIBE\\r\\nNOTIFY\\r\\nINVITE\\r\\nACK\\r\\nCANCEL\\r\\nBYE\\r\\nREFER\\r\\nINFO\\r\\nOPTIONS\\r\\nMESSAGE","Via":"SIP/2.0/UDP 192.168.26.111:5060;branch=z9hG4bK1vxcouwp4r78j;rport=5060\\r\\nSIP/2.0/UDP 192.168.26.1:5678;branch=z9hG4bK-524287-1---b1f649005b368755;rport=5678","Contact":"<sip:pengxli@192.168.26.1:5678>","To":"<sip:9992801029@10.140.254.38>","Content-Length":"335","Content-Type":"application/sdp"}}}';
		$session = new Session($strSession);
		$this->assertEquals($session->getId(), '35b00c154f2fecacba37fad74e64a7e2');
		$this->assertEquals($session->getAccountId(), '1');
		$this->assertEquals($session->getTimestamp(), '2017-06-08T03:40:19.283Z');
		$this->assertEquals($session->getUserType(), 'HUMAN');
		$this->assertEquals($session->getInitialText(), null);
		$this->assertEquals($session->getCallId(), 'c5b298fc0785fda9029f7f3b5aeef7ab');
		$to = $session->getTo();
		$this->assertEquals($to["id"], '9992801029');
		$this->assertEquals($to["e164Id"], '9992801029');
		$this->assertEquals($to["name"], '9992801029');
		$this->assertEquals($to["channel"], 'VOICE');
		$this->assertEquals($to["network"], 'SIP');
		$from = $session->getFrom();
		$this->assertEquals($from["id"], 'pengxli');
		$this->assertEquals($from["e164Id"], 'pengxli');
		$this->assertEquals($from["name"], 'pengxli');
		$this->assertEquals($from["channel"], 'VOICE');
		$this->assertEquals($from["network"], 'SIP');
	}

}
?>