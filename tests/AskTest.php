<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class AskTest extends PHPUnit_Framework_TestCase
{
    
    public function testAskWithMinOptions() {
      $tropo = new Tropo();
      $params = array(
        'choices' => '[1 DIGIT]'
        );
      $tropo->ask("Please say a digit.", $params);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"choices":{"value":"[1 DIGIT]"},"say":[{"value":"Please say a digit."}]}}]}');
    }

    public function testAskWithExtraSayOptions() {
        $tropo = new Tropo();
        $event = array(
            'timeout' => 'Sorry, I did not hear anything.',
            'nomatch:1' => "Don't think that was a year.",
            'nomatch:2' => 'Nope, still not a year.'
            );
        $params = array(
            'choices' => '[4 DIGITS]',
            'event' => $event,
            'attempts' => 3
            );
        $tropo->ask("What is your birth year?", $params);
        $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"attempts":3,"choices":{"value":"[4 DIGITS]"},"say":[{"event":"timeout","value":"Sorry, I did not hear anything."},{"event":"nomatch:1","value":"Don'."'".'t think that was a year."},{"event":"nomatch:2","value":"Nope, still not a year."},{"value":"What is your birth year?"}]}}]}');
    }

    public function testAskWithAllOptions() {
        $tropo = new Tropo();
        $allowSignals = array('quit', 'exit');
        $event = array(
            'timeout' => 'Sorry, I did not hear anything.',
            'nomatch:1' => "Don't think that was a year.",
            'nomatch:2' => 'Nope, still not a year.'
            );
        $params = array(
            'choices' => '[4 DIGITS]',
            'mode' => 'dtmf',
            'terminator' => '#',
            'allowSignals' => $allowSignals,
            'attempts' => 3,
            'bargein' => true,
            'interdigitTimeout' => 5.0,
            'minConfidence' => 30.0,
            'name' => 'foo',
            'recognizer' => Recognizer::$US_English,
            'required' => true,
            'event' => $event,
            'sensitivity' => 0.5,
            'speechCompleteTimeout' => 0.5,
            'speechIncompleteTimeout' => 0.5,
            'timeout' => 30.0,
            'voice' => Voice::$US_English_female_allison,
            'promptLogSecurity' => 'suppress',
            'asrLogSecurity' => 'mask',
            'maskTemplate' => 'XXD-'
            );
        $tropo->ask("What is your birth year?", $params);
        $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"attempts":3,"bargein":true,"choices":{"value":"[4 DIGITS]","mode":"dtmf","terminator":"#"},"minConfidence":30,"name":"foo","required":true,"say":[{"event":"timeout","value":"Sorry, I did not hear anything."},{"event":"nomatch:1","value":"Don'."'".'t think that was a year."},{"event":"nomatch:2","value":"Nope, still not a year."},{"value":"What is your birth year?"}],"timeout":30,"voice":"allison","allowSignals":["quit","exit"],"recognizer":"en-us","interdigitTimeout":5,"sensitivity":0.5,"speechCompleteTimeout":0.5,"speechIncompleteTimeout":0.5,"promptLogSecurity":"suppress","asrLogSecurity":"mask","maskTemplate":"XXD-"}}]}');
    }

    public function testCreateAskObject() {
        $tropo = new Tropo();
        $ask = new Ask(null, null, new Choices('[1 DIGIT]'), null, null, null, array(new Say("Please say a digit.")));
        $tropo ->ask($ask);
        $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"choices":{"value":"[1 DIGIT]"},"say":[{"value":"Please say a digit."}]}}]}');
    }

    public function testCreateAskObject1() {
        $tropo = new Tropo();
        $ask = new Ask(3, null, new Choices('[4 DIGITS]'), null, null, null, array(new Say("Sorry, I did not hear anything.", null, "timeout"), new Say("Don't think that was a year.", null , "nomatch:1"), new Say("Nope, still not a year.", null, "nomatch:2"), new Say("What is your birth year?")));
        $tropo ->ask($ask);
        $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"attempts":3,"choices":{"value":"[4 DIGITS]"},"say":[{"event":"timeout","value":"Sorry, I did not hear anything."},{"event":"nomatch:1","value":"Don'."'".'t think that was a year."},{"event":"nomatch:2","value":"Nope, still not a year."},{"value":"What is your birth year?"}]}}]}');
    }

    public function testCreateAskObject2() {
        $tropo = new Tropo();
        $choices = new Choices("[4 DIGITS]", "dtmf", "#");
        $say = array(
            new Say("Sorry, I did not hear anything.", null, "timeout"),
            new Say("Don't think that was a year.", null , "nomatch:1"),
            new Say("Nope, still not a year.", null, "nomatch:2"),
            new Say("What is your birth year?")
            );
        $allowSignals = array('quit', 'exit');
        $ask = new Ask(3, true, $choices, 30.0, "foo", true, $say, 30.0, Voice::$US_English_female_allison, $allowSignals, Recognizer::$US_English, 5.0, 0.5, 0.5, 0.5, "suppress", "mask", "XXD-");
        $tropo ->ask($ask);
        $this->assertEquals(sprintf($tropo), '{"tropo":[{"ask":{"attempts":3,"bargein":true,"choices":{"value":"[4 DIGITS]","mode":"dtmf","terminator":"#"},"minConfidence":30,"name":"foo","required":true,"say":[{"event":"timeout","value":"Sorry, I did not hear anything."},{"event":"nomatch:1","value":"Don'."'".'t think that was a year."},{"event":"nomatch:2","value":"Nope, still not a year."},{"value":"What is your birth year?"}],"timeout":30,"voice":"allison","allowSignals":["quit","exit"],"recognizer":"en-us","interdigitTimeout":5,"sensitivity":0.5,"speechCompleteTimeout":0.5,"speechIncompleteTimeout":0.5,"promptLogSecurity":"suppress","asrLogSecurity":"mask","maskTemplate":"XXD-"}}]}');
    }
}
?>