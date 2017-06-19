<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class RecordTest extends PHPUnit_Framework_TestCase
{

    public function testRecordWithMinOptions() {
      $tropo = new Tropo();
      $record = array(
        'url' => 'http://192.168.26.203/tropo-webapi-php/upload_file.php',
        'name' => 'foo'
        );
      $tropo->record($record);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"record":{"url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","name":"foo"}}]}');
    }

    public function testRecordWithAllOptions() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $event = array(
        'timeout' => 'Sorry, I did not hear anything. Please call back.'
        );
      $transcription = array(
        'id' => '1234',
        'url' => 'mailto:you@yourmail.com',
        'emailFormat' => 'omit'
        );
      $record = array(
        'attempts' => 2,
        'asyncUpload' => false,
        'allowSignals' => $allowSignals,
        'bargein' => true,
        'beep' => true,
        'choices' => '*',
        'say' => 'Please leave a message.',
        'event' => $event,
        'format' => AudioFormat::$mp3,
        'maxSilence' => 5.0,
        'maxTime' => 30.0,
        'method' => 'POST',
        'name' => 'foo',
        'required' => true,
        'transcription' => $transcription,
        'url' => 'http://192.168.26.203/tropo-webapi-php/upload_file.php',
        'password' => '111111',
        'username' => 'root',
        'timeout' => 30.0,
        'interdigitTimeout' => 5.0,
        'voice' => Voice::$US_English_female_allison,
        'promptLogSecurity' => 'suppress'
        );
      $tropo->record($record);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"record":{"attempts":2,"allowSignals":["exit","quit"],"bargein":true,"beep":true,"choices":{"terminator":"*"},"format":"audio/mp3","maxSilence":5,"maxTime":30,"method":"POST","password":"111111","required":true,"say":[{"value":"Please leave a message."},{"event":"timeout","value":"Sorry, I did not hear anything. Please call back."}],"timeout":30,"transcription":{"id":"1234","url":"mailto:you@yourmail.com","emailFormat":"omit"},"username":"root","url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","voice":"allison","interdigitTimeout":5,"asyncUpload":false,"name":"foo","promptLogSecurity":"suppress"}}]}');
    }


    public function testCreateMinObject() {
      $tropo = new Tropo();
      $record = new Record(null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, "http://192.168.26.203/tropo-webapi-php/upload_file.php", null, null, null, null, "foo", null);
      $tropo->record($record);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"record":{"url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","name":"foo"}}]}');
    }

    public function testCreateObject() {
      $tropo = new Tropo();
      $allowSignals = array('exit', 'quit');
      $choices = new Choices(null, null, "*");
      $say = array(
        new Say("Please leave a message."),
        new Say("Sorry, I did not hear anything. Please call back.", null , "timeout")
        );
      $transcription = new Transcription("mailto:you@yourmail.com", "1234", "omit");
      $record = new Record(2, $allowSignals, true, true, $choices, AudioFormat::$mp3, 5.0, 30.0, "POST", "111111", true, $say, 30.0, $transcription, "root", "http://192.168.26.203/tropo-webapi-php/upload_file.php", Voice::$US_English_female_allison, null, 5.0, false, "foo", "suppress");
      $tropo->record($record);
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"record":{"attempts":2,"allowSignals":["exit","quit"],"bargein":true,"beep":true,"choices":{"terminator":"*"},"format":"audio/mp3","maxSilence":5,"maxTime":30,"method":"POST","password":"111111","required":true,"say":[{"value":"Please leave a message."},{"event":"timeout","value":"Sorry, I did not hear anything. Please call back."}],"timeout":30,"transcription":{"id":"1234","url":"mailto:you@yourmail.com","emailFormat":"omit"},"username":"root","url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","voice":"allison","interdigitTimeout":5,"asyncUpload":false,"name":"foo","promptLogSecurity":"suppress"}}]}');
    }

}
?>