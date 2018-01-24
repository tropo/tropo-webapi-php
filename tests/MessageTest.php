<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class MessageTest extends PHPUnit_Framework_TestCase
{

    public function testMessageWithMinOptions() {
      $tropo = new Tropo();
      $params = array(
        'to' => 'sip:pengxli@192.168.26.1:5678'
        );
      $tropo->message("This is an announcement.", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":{"value":"This is an announcement."},"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testMessageWithExtraSayOptiions() {
      $tropo = new Tropo();
      $say = "Remember, you have a meeting at 2 PM.";
      $params = array(
        'say' => $say,
        'to' => 'sip:pengxli@192.168.26.1:5678'
        );
      $tropo->message("This is an announcement.", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":[{"value":"This is an announcement."},{"value":"Remember, you have a meeting at 2 PM."}],"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testMessageWithAllOptions() {
      $tropo = new Tropo();
      $say = array('Remember, you have a meeting at 2 PM.', 'This is tropo.com.');
      $to = array('sip:pengxli@192.168.26.1:5678', 'sip:pengxli@172.16.72.131:5678');
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $params = array(
        'say' => $say,
        'to' => $to,
        'answerOnMedia' => false,
        'channel' => Channel::$voice,
        'from' => '3055551000',
        'network' => Network::$sip,
        'required' => true,
        'timeout' => 60,
        'voice' => Voice::$US_English_female_allison,
        'promptLogSecurity' => 'suppress',
        'headers' => $headers
        );
      $tropo->message("This is an announcement.", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":[{"value":"This is an announcement."},{"value":"Remember, you have a meeting at 2 PM."},{"value":"This is tropo.com."}],"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@172.16.72.131:5678"],"channel":"VOICE","network":"SIP","from":"3055551000","voice":"allison","timeout":60,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"required":true,"promptLogSecurity":"suppress"}}]}');
    }

    public function testCreateMinObject() {
      $tropo = new Tropo();
      $message = new Message(new Say("This is an announcement."), "sip:pengxli@192.168.26.1:5678");
      $tropo->message($message);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":{"value":"This is an announcement."},"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testCreateObject1() {
      $tropo = new Tropo();
      $say = array(
        new Say("This is an announcement."),
        new Say("Remember, you have a meeting at 2 PM.")
        );
      $message = new Message($say, "sip:pengxli@192.168.26.1:5678");
      $tropo->message($message);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":[{"value":"This is an announcement."},{"value":"Remember, you have a meeting at 2 PM."}],"to":"sip:pengxli@192.168.26.1:5678"}}]}');
    }

    public function testCreateObject2() {
      $tropo = new Tropo();
      $say = array(
        new Say("This is an announcement."),
        new Say("Remember, you have a meeting at 2 PM."),
        new Say("This is tropo.com.")
        );
      $to = array(
        'sip:pengxli@192.168.26.1:5678',
        'sip:pengxli@172.16.72.131:5678'
        );
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $message = new Message($say, $to, Channel::$voice, Network::$sip, "3055551000", Voice::$US_English_female_allison, 60, false, $headers, null, true, "suppress");
      $tropo->message($message);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"message":{"say":[{"value":"This is an announcement."},{"value":"Remember, you have a meeting at 2 PM."},{"value":"This is tropo.com."}],"to":["sip:pengxli@192.168.26.1:5678","sip:pengxli@172.16.72.131:5678"],"channel":"VOICE","network":"SIP","from":"3055551000","voice":"allison","timeout":60,"answerOnMedia":false,"headers":{"foo":"bar","bling":"baz"},"required":true,"promptLogSecurity":"suppress"}}]}');
    }
}
?>