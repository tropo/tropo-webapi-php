<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class TransferTest extends PHPUnit_Framework_TestCase
{

    public function testTransferWithMinOptions() {
      $tropo = new Tropo();
      $params = array(
        'name' => 'foo'
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","name":"foo"}}]}');
    }

    public function testTransferWithMinOptions1() {
      $tropo = new Tropo();
      $params = array(
        'name' => 'foo'
        );
      $tropo->transfer(array("sip:pengxli@172.16.72.131:5678", "sip:pengxli@192.168.26.1:5678"), $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":["sip:pengxli@172.16.72.131:5678","sip:pengxli@192.168.26.1:5678"],"name":"foo"}}]}');
    }

    public function testTransferWithChoicesOptions() {
      $tropo = new Tropo();
      $choices = new Choices(null, null, "#");
      $params = array(
        'name' => 'foo',
        'choices' => $choices,
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","choices":{"terminator":"#"},"name":"foo"}}]}');
    }

    public function testTransferWithChoicesOptions1() {
      $tropo = new Tropo();
      $params = array(
        'name' => 'foo',
        'choices' => '#',
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","choices":{"terminator":"#"},"name":"foo"}}]}');
    }

    public function testTransferWithChoicesAndTerminatorOptions() {
      $tropo = new Tropo();
      $choices = new Choices(null, null, "#");
      $params = array(
        'name' => 'foo',
        'choices' => $choices,
        'terminator' => '*',
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","choices":{"terminator":"*"},"name":"foo"}}]}');
    }

    public function testTransferWithChoicesAndTerminatorOptions1() {
      $tropo = new Tropo();
      $params = array(
        'name' => 'foo',
        'choices' => '#',
        'terminator' => '*',
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","choices":{"terminator":"*"},"name":"foo"}}]}');
    }

    public function testTransferWithOnOptions() {
      $tropo = new Tropo();
      $on = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $params = array(
        'name' => 'foo',
        'ringRepeat' => 2,
        'on' => $on,
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","ringRepeat":2,"on":{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},"name":"foo"}}]}');
    }

    public function testTransferWithAllOptions() {
      $tropo = new Tropo();
      $allowSignals = array("exit", "quit");
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $onRing = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $say = array(
          new Say("Sorry. Please enter you 5 digit account number again.", null, "nomatch"),
          new Say("Sorry, I did not hear anything.", null , "timeout"),
          new Say("Please enter 5 digit account number.")
          );
      $ask = new Ask(3, true, new Choices("[5 DIGITS]", "dtmf", null), null, "ask", true, $say);
      $onConnect = new On("connect", null, null, null, $ask);
      $on = array($onRing, $onConnect);
      $params = array(
        'from' => '14155551212',
        'timeout' => 30.0,
        'answerOnMedia' => false,
        'name' => 'foo',
        'required' => true,
        'allowSignals' => $allowSignals,
        'machineDetection' => false,
        'terminator' => '#',
        'headers' => $headers,
        'interdigitTimeout' => 5.0,
        'ringRepeat' => 2,
        'playTones' => true,
        'on' => $on,
        'voice' => Voice::$US_English_female_allison,
        'callbackUrl' => 'http://192.168.26.203/result.php',
        'promptLogSecurity' => 'suppress',
        'label' => 'transferLabel'
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","answerOnMedia":false,"choices":{"terminator":"#"},"from":"14155551212","ringRepeat":2,"timeout":30,"on":[{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},{"event":"connect","ask":{"attempts":3,"bargein":true,"choices":{"value":"[5 DIGITS]","mode":"dtmf"},"name":"ask","required":true,"say":[{"event":"nomatch","value":"Sorry. Please enter you 5 digit account number again."},{"event":"timeout","value":"Sorry, I did not hear anything."},{"value":"Please enter 5 digit account number."}]}}],"allowSignals":["exit","quit"],"headers":{"foo":"bar","bling":"baz"},"machineDetection":false,"voice":"allison","name":"foo","required":true,"interdigitTimeout":5,"playTones":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"transferLabel"}}]}');
    }

    public function testTransferWithAllOptions1() {
      $tropo = new Tropo();
      $allowSignals = array("exit", "quit");
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $onRing = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $say = array(
          new Say("Sorry. Please enter you 5 digit account number again.", null, "nomatch"),
          new Say("Sorry, I did not hear anything.", null , "timeout"),
          new Say("Please enter 5 digit account number.")
          );
      $ask = new Ask(3, true, new Choices("[5 DIGITS]", "dtmf", null), null, "ask", true, $say);
      $onConnect = new On("connect", null, null, null, $ask);
      $on = array($onRing, $onConnect);
      $params = array(
        'from' => '14155551212',
        'timeout' => 30.0,
        'answerOnMedia' => false,
        'name' => 'foo',
        'required' => true,
        'allowSignals' => $allowSignals,
        'machineDetection' => 'For the most accurate results, the "introduction" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.',
        'terminator' => '#',
        'headers' => $headers,
        'interdigitTimeout' => 5.0,
        'ringRepeat' => 2,
        'playTones' => true,
        'on' => $on,
        'voice' => Voice::$US_English_female_allison,
        'callbackUrl' => 'http://192.168.26.203/result.php',
        'promptLogSecurity' => 'suppress',
        'label' => 'transferLabel'
        );
      $tropo->transfer("sip:pengxli@172.16.72.131:5678", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","answerOnMedia":false,"choices":{"terminator":"#"},"from":"14155551212","ringRepeat":2,"timeout":30,"on":[{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},{"event":"connect","ask":{"attempts":3,"bargein":true,"choices":{"value":"[5 DIGITS]","mode":"dtmf"},"name":"ask","required":true,"say":[{"event":"nomatch","value":"Sorry. Please enter you 5 digit account number again."},{"event":"timeout","value":"Sorry, I did not hear anything."},{"value":"Please enter 5 digit account number."}]}}],"allowSignals":["exit","quit"],"headers":{"foo":"bar","bling":"baz"},"machineDetection":{"introduction":"For the most accurate results, the \"introduction\" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.","voice":"allison"},"voice":"allison","name":"foo","required":true,"interdigitTimeout":5,"playTones":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"transferLabel"}}]}');
    }


    public function testCreateMinObject() {
      $tropo = new Tropo();
      $transfer = new Transfer("sip:pengxli@172.16.72.131:5678", null, null, null, null, null, null, null, null, null, null, "foo");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","name":"foo"}}]}');
    }

    public function testCreateObject() {
      $tropo = new Tropo();
      $transfer = new Transfer(array("sip:pengxli@172.16.72.131:5678", "sip:pengxli@192.168.26.1:5678"), null, null, null, null, null, null, null, null, null, null, "foo");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":["sip:pengxli@172.16.72.131:5678","sip:pengxli@192.168.26.1:5678"],"name":"foo"}}]}');
    }

    public function testCreateObject1() {
      $tropo = new Tropo();
      $choices = new Choices(null, null, "#");
      $transfer = new Transfer(array("sip:pengxli@172.16.72.131:5678", "sip:pengxli@192.168.26.1:5678"), null, $choices, null, null, null, null, null, null, null, null, "foo");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":["sip:pengxli@172.16.72.131:5678","sip:pengxli@192.168.26.1:5678"],"choices":{"terminator":"#"},"name":"foo"}}]}');
    }

    public function testCreateObject2() {
      $tropo = new Tropo();
      $on = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $transfer = new Transfer("sip:pengxli@172.16.72.131:5678", null, null, null, 2, null, $on, null, null, null, null, "foo");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","ringRepeat":2,"on":{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},"name":"foo"}}]}');
    }

    public function testCreateObject3() {
      $tropo = new Tropo();
      $choices = new Choices(null, null, "#");
      $allowSignals = array("exit", "quit");
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $onRing = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $say = array(
        new Say("Sorry. Please enter you 5 digit account number again.", null, "nomatch"),
        new Say("Sorry, I did not hear anything.", null , "timeout"),
        new Say("Please enter 5 digit account number.")
        );
      $ask = new Ask(3, true, new Choices("[5 DIGITS]", "dtmf", null), null, "ask", true, $say);
      $onConnect = new On("connect", null, null, null, $ask);
      $on = array($onRing, $onConnect);
      $transfer = new Transfer("sip:pengxli@172.16.72.131:5678", false, $choices, "14155551212", 2, 30.0, $on, $allowSignals, $headers, false, Voice::$US_English_female_allison, "foo", true, 5.0, true, "http://192.168.26.203/result.php", "suppress", "transferLabel");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","answerOnMedia":false,"choices":{"terminator":"#"},"from":"14155551212","ringRepeat":2,"timeout":30,"on":[{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},{"event":"connect","ask":{"attempts":3,"bargein":true,"choices":{"value":"[5 DIGITS]","mode":"dtmf"},"name":"ask","required":true,"say":[{"event":"nomatch","value":"Sorry. Please enter you 5 digit account number again."},{"event":"timeout","value":"Sorry, I did not hear anything."},{"value":"Please enter 5 digit account number."}]}}],"allowSignals":["exit","quit"],"headers":{"foo":"bar","bling":"baz"},"machineDetection":false,"voice":"allison","name":"foo","required":true,"interdigitTimeout":5,"playTones":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"transferLabel"}}]}');
    }

    public function testCreateObject4() {
      $tropo = new Tropo();
      $choices = new Choices(null, null, "#");
      $allowSignals = array("exit", "quit");
      $headers = array('foo' => 'bar', 'bling' => 'baz');
      $machineDetection = 'For the most accurate results, the "introduction" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.';
      $onRing = new On("ring", null, new Say("http://www.phono.com/audio/holdmusic.mp3"));
      $say = array(
        new Say("Sorry. Please enter you 5 digit account number again.", null, "nomatch"),
        new Say("Sorry, I did not hear anything.", null , "timeout"),
        new Say("Please enter 5 digit account number.")
        );
      $ask = new Ask(3, true, new Choices("[5 DIGITS]", "dtmf", null), null, "ask", true, $say);
      $onConnect = new On("connect", null, null, null, $ask);
      $on = array($onRing, $onConnect);
      $transfer = new Transfer("sip:pengxli@172.16.72.131:5678", false, $choices, "14155551212", 2, 30.0, $on, $allowSignals, $headers, $machineDetection, Voice::$US_English_female_allison, "foo", true, 5.0, true, "http://192.168.26.203/result.php", "suppress", "transferLabel");
      $tropo->transfer($transfer);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"transfer":{"to":"sip:pengxli@172.16.72.131:5678","answerOnMedia":false,"choices":{"terminator":"#"},"from":"14155551212","ringRepeat":2,"timeout":30,"on":[{"event":"ring","say":{"value":"http://www.phono.com/audio/holdmusic.mp3"}},{"event":"connect","ask":{"attempts":3,"bargein":true,"choices":{"value":"[5 DIGITS]","mode":"dtmf"},"name":"ask","required":true,"say":[{"event":"nomatch","value":"Sorry. Please enter you 5 digit account number again."},{"event":"timeout","value":"Sorry, I did not hear anything."},{"value":"Please enter 5 digit account number."}]}}],"allowSignals":["exit","quit"],"headers":{"foo":"bar","bling":"baz"},"machineDetection":{"introduction":"For the most accurate results, the \"introduction\" should be long enough to give Tropo time to detect a human or machine. The longer the introduction, the more time we have to determine how the call was answered.","voice":"allison"},"voice":"allison","name":"foo","required":true,"interdigitTimeout":5,"playTones":true,"callbackUrl":"http://192.168.26.203/result.php","promptLogSecurity":"suppress","label":"transferLabel"}}]}');
    }

}
?>