<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class AskTest extends PHPUnit_Framework_TestCase
{
    public $askJson;
    public $expected;
    
    public function AskTest() {
      $this->askJson = '{"bargein":true,"choices":{"value":"[5 DIGITS]"},"name":"foo","required":true,"say":[{"value":"Please say your account number"}],"timeout":30}';
      $this->expected = '{"tropo":[{"ask":' . $this->askJson . '}]}';
    }
    
    public function testCreateAskObject()
    {
      $say = array(new Say("Please say your account number"));
      $choices = new Choices("[5 DIGITS]");
      $ask = new Ask(NULL, true, $choices, NULL, "foo", true, $say, 30);
      $this->assertEquals($this->askJson, sprintf($ask));
    }


    public function testAskFromObject()
    {
      $say = array(new Say("Please say your account number"));
      $choices = new Choices("[5 DIGITS]");
      $ask = new Ask(NULL, true, $choices, NULL, "foo", true, $say, 30);
      $tropo = new Tropo();
      $tropo->Ask($ask);
      $this->assertEquals($this->expected, sprintf($tropo));
    }
}
?>