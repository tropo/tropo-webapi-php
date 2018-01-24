<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class ConferenceTest extends PHPUnit_Framework_TestCase
{

    public function testConferenceWithMinOptions() {
      $tropo = new Tropo();
      $tropo->conference("1234");
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234"}}]}');
    }

    public function testConferenceWithAllOptions() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $params = array(
        'allowSignals' => $allowSignals,
        'interdigitTimeout' => 5.0,
        'joinPrompt' => true,
        'leavePrompt' => true,
        'mute' => false,
        'playTones' => true,
        'required' => true,
        'terminator' => '*',
        'promptLogSecurity' => 'suppress',
        );
      $tropo->conference("1234", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234","mute":false,"playTones":true,"required":true,"terminator":"*","allowSignals":["exit","quit"],"interdigitTimeout":5,"promptLogSecurity":"suppress","joinPrompt":true,"leavePrompt":true}}]}');
    }

    public function testConferenceWithAllOptions1() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $joinPrompt = array(
        'value' => 'I am coming.',
        'voice' => Voice::$US_English_female_allison
        );
      $leavePrompt = array(
        'value' => 'I am leaving.',
        'voice' => Voice::$US_English_female_allison
        );
      $params = array(
        'allowSignals' => $allowSignals,
        'interdigitTimeout' => 5.0,
        'joinPrompt' => $joinPrompt,
        'leavePrompt' => $leavePrompt,
        'mute' => false,
        'playTones' => true,
        'required' => true,
        'terminator' => '*',
        'promptLogSecurity' => 'suppress',
        );
      $tropo->conference("1234", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234","mute":false,"playTones":true,"required":true,"terminator":"*","allowSignals":["exit","quit"],"interdigitTimeout":5,"promptLogSecurity":"suppress","joinPrompt":{"value":"I am coming.","voice":"allison"},"leavePrompt":{"value":"I am leaving.","voice":"allison"}}}]}');
    }

    public function testCreateMinObject() {
      $tropo = new Tropo();
      $conference = new Conference(null, "1234");
      $tropo->conference($conference);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234"}}]}');
    }

    public function testCreateObject1() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $conference = new Conference(null, "1234", false, null, true, true, "*", $allowSignals, 5.0, true, true, null, "suppress");
      $tropo->conference($conference);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234","mute":false,"playTones":true,"required":true,"terminator":"*","allowSignals":["exit","quit"],"interdigitTimeout":5,"promptLogSecurity":"suppress","joinPrompt":true,"leavePrompt":true}}]}');
    }

    public function testCreateObject2() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $joinPrompt = array(
        'value' => 'I am coming.',
        'voice' => Voice::$US_English_female_allison
        );
      $leavePrompt = array(
        'value' => 'I am leaving.',
        'voice' => Voice::$US_English_female_allison
        );
      $conference = new Conference(null, "1234", false, null, true, true, "*", $allowSignals, 5.0, $joinPrompt, $leavePrompt, null, "suppress");
      $tropo->conference($conference);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"conference":{"id":"1234","mute":false,"playTones":true,"required":true,"terminator":"*","allowSignals":["exit","quit"],"interdigitTimeout":5,"promptLogSecurity":"suppress","joinPrompt":{"value":"I am coming.","voice":"allison"},"leavePrompt":{"value":"I am leaving.","voice":"allison"}}}]}');
    }
}
?>