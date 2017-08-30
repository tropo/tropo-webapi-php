<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class AnswerTest extends PHPUnit_Framework_TestCase
{

    public function testCallWithMinOptions() {
      $tropo = new Tropo();
      $tropo->answer();
      $params = array("name"=>"say");
      $tropo->say("Hello, you were the first to answer.",$params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"answer":{}},{"say":[{"value":"Hello, you were the first to answer.","name":"say"}]}]}');
    }

    public function testCallWithMinOptions1() {
      $tropo = new Tropo();
      $tropo->answer(null);
      $params = array("name"=>"say");
      $tropo->say("Hello, you were the first to answer.",$params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"answer":{}},{"say":[{"value":"Hello, you were the first to answer.","name":"say"}]}]}');
    }

    public function testCallWithExtraToOptiions() {
      $tropo = new Tropo();
      $params = array(
        'headers' => array(
          'P-Header' => 'value goes here',
          'Remote-Party-ID' => '"John Doe"<sip:jdoe@foo.com>;party=calling;id-type=subscriber;privacy=full;screen=yes'
        ));
      $tropo->answer($params);
      $params = array("name"=>"say");
      $tropo->say("Hello, you were the first to answer.",$params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"answer":{"headers":{"P-Header":"value goes here","Remote-Party-ID":"\"John Doe\"<sip:jdoe@foo.com>;party=calling;id-type=subscriber;privacy=full;screen=yes"}}},{"say":[{"value":"Hello, you were the first to answer.","name":"say"}]}]}');
    }

    public function testCreateMinObject() {
      $tropo = new Tropo();
      $answer = new Answer();
      $tropo->answer($answer);
      $params = array("name"=>"say");
      $tropo->say("Hello, you were the first to answer.",$params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"answer":{}},{"say":[{"value":"Hello, you were the first to answer.","name":"say"}]}]}');
    }

    public function testCreateMinObject1() {
      $tropo = new Tropo();
      $headers = array(
        'P-Header' => 'value goes here',
        'Remote-Party-ID' => '"John Doe"<sip:jdoe@foo.com>;party=calling;id-type=subscriber;privacy=full;screen=yes'
      );
      $answer = new Answer($headers);
      $tropo->answer($answer);
      $params = array("name"=>"say");
      $tropo->say("Hello, you were the first to answer.",$params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"answer":{"headers":{"P-Header":"value goes here","Remote-Party-ID":"\"John Doe\"<sip:jdoe@foo.com>;party=calling;id-type=subscriber;privacy=full;screen=yes"}}},{"say":[{"value":"Hello, you were the first to answer.","name":"say"}]}]}');
    }

}
?>