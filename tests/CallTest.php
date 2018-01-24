<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class CallTest extends PHPUnit_Framework_TestCase
{

    public function testCallWithMinOptions() {
      $tropo = new Tropo();
      $tropo->call("sip:pengxli@192.168.26.1:5678");
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testCallWithExtraToOptiions() {
      $tropo = new Tropo();
      $call = array('sip:pengxli@192.168.26.1:5678', 'sip:pengxli@192.168.26.206:5678');
      $tropo->call($call);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@192.168.26.206:5678"]}}]}');
    }

    public function testCallWithAllOptions() {
      $tropo = new Tropo();
      $call = array('sip:pengxli@192.168.26.1:5678', 'sip:pengxli@192.168.26.206:5678');
      $allowSignals = array('exit', 'quit');
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $params = array(
        'allowSignals' => $allowSignals,
        'answerOnMedia' => false,
        'channel' => Channel::$voice,
        'from' => '3055551000',
        'headers' => $headers,
        'machineDetection' => false,
        'network' => Network::$sip,
        'required' => true,
        'timeout' => 30.0,
        'voice' => Voice::$US_English_female_allison,
        'callbackUrl' => 'http://192.168.26.203/result.php',
        'promptLogSecurity' => 'suppress',
        'label' => 'callLabel'
        );
      $tropo->call($call, $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@192.168.26.206:5678"],"from":"3055551000","network":"SIP","channel":"VOICE","timeout":30,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"allowSignals":["exit","quit"],"machineDetection":false,"voice":"allison","required":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"callLabel"}}]}');
    }

    public function testCallWithAllOptions1() {
      $tropo = new Tropo();
      $call = array('sip:pengxli@192.168.26.1:5678', 'sip:pengxli@192.168.26.206:5678');
      $allowSignals = array('exit', 'quit');
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $params = array(
        'allowSignals' => $allowSignals,
        'answerOnMedia' => false,
        'channel' => Channel::$voice,
        'from' => '3055551000',
        'headers' => $headers,
        'machineDetection' => 'For the most accurate results, the "introduction" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.',
        'network' => Network::$sip,
        'required' => true,
        'timeout' => 30.0,
        'voice' => Voice::$US_English_female_allison,
        'callbackUrl' => 'http://192.168.26.203/result.php',
        'promptLogSecurity' => 'suppress',
        'label' => 'callLabel'
        );
      $tropo->call($call, $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@192.168.26.206:5678"],"from":"3055551000","network":"SIP","channel":"VOICE","timeout":30,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"allowSignals":["exit","quit"],"machineDetection":{"introduction":"For the most accurate results, the \"introduction\" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.","voice":"allison"},"voice":"allison","required":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"callLabel"}}]}');
    }

    public function testCreateMinObject() {
      $tropo = new Tropo();
      $call = new Call("sip:pengxli@192.168.26.1:5678");
      $tropo->call($call);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testCreateObject1() {
      $tropo = new Tropo();
      $to = array("sip:pengxli@192.168.26.1:5678", "sip:pengxli@172.16.72.131:5678");
      $call = new Call($to);
      $tropo->call($call);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@172.16.72.131:5678"]}}]}');
    }

    public function testCreateObject2() {
      $tropo = new Tropo();
      $to = array("sip:pengxli@192.168.26.1:5678", "sip:pengxli@172.16.72.131:5678");
      $allowSignals = array('exit', 'quit');
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $call = new Call($to, "3055551000", Network::$sip, Channel::$voice, false, 30.0, $headers, null, $allowSignals, false, Voice::$US_English_female_allison, null, true, "http://192.168.26.203/result.php", "suppress", "callLabel");
      $tropo->call($call);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@172.16.72.131:5678"],"from":"3055551000","network":"SIP","channel":"VOICE","timeout":30,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"allowSignals":["exit","quit"],"machineDetection":false,"voice":"allison","required":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"callLabel"}}]}');
    }

    public function testCreateObject3() {
      $tropo = new Tropo();
      $to = array("sip:pengxli@192.168.26.1:5678", "sip:pengxli@172.16.72.131:5678");
      $allowSignals = array('exit', 'quit');
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $machineDetection = 'For the most accurate results, the "introduction" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.';
      $call = new Call($to, "3055551000", Network::$sip, Channel::$voice, false, 30.0, $headers, null, $allowSignals, $machineDetection, Voice::$US_English_female_allison, null, true, "http://192.168.26.203/result.php", "suppress", "callLabel");
      $tropo->call($call);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"call":{"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@172.16.72.131:5678"],"from":"3055551000","network":"SIP","channel":"VOICE","timeout":30,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"allowSignals":["exit","quit"],"machineDetection":{"introduction":"For the most accurate results, the \"introduction\" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.","voice":"allison"},"voice":"allison","required":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"callLabel"}}]}');
    }
}
?>