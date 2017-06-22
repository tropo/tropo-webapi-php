<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class StartRecordingTest extends PHPUnit_Framework_TestCase
{    
    public function testStartRecordingWithMinOptions() {
      $tropo = new Tropo();
      $startRecording = array(
        'url' => 'http://192.168.26.203/tropo-webapi-php/upload_file.php',
        );
      $tropo->startRecording($startRecording);
      $say = new Say("I am now recording!", null, null, null, null, "say");
      $tropo->say($say);
      $tropo->stopRecording();
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"startRecording":{"url":"http://192.168.26.203/tropo-webapi-php/upload_file.php"}},{"say":[{"value":"I am now recording!","name":"say"}]},{"stopRecording":"null"}]}');
    }

    public function testRecordWithAllOptions() {
      $tropo = new Tropo();
      $startRecording = array(
        'asyncUpload' => false,
        'format' => AudioFormat::$au,
        'method' => 'POST',
        'url' => 'http://192.168.26.203/tropo-webapi-php/upload_file.php',
        'username' => 'root',
        'password' => '111111',
        'transcriptionOutURI' => 'mailto:you@yourmail.com',
        'transcriptionEmailFormat' => 'plain',
        'transcriptionID' => '1234'
        );
      $tropo->startRecording($startRecording);
      $say = new Say("I am now recording!", null, null, null, null, "say");
      $tropo->say($say);
      $tropo->stopRecording();
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"startRecording":{"format":"audio/au","method":"POST","password":"111111","url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","username":"root","transcriptionID":"1234","transcriptionEmailFormat":"plain","transcriptionOutURI":"mailto:you@yourmail.com","asyncUpload":false}},{"say":[{"value":"I am now recording!","name":"say"}]},{"stopRecording":"null"}]}');
    }

    public function testCreateMinObject() {
      $tropo = new Tropo();
      $startRecording = new StartRecording(null, null, null, 'http://192.168.26.203/tropo-webapi-php/upload_file.php');
      $tropo->startRecording($startRecording);
      $say = new Say("I am now recording!", null, null, null, null, "say");
      $tropo->say($say);
      $tropo->stopRecording();
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"startRecording":{"url":"http://192.168.26.203/tropo-webapi-php/upload_file.php"}},{"say":[{"value":"I am now recording!","name":"say"}]},{"stopRecording":"null"}]}');
    }

    public function testCreateObject() {
      $tropo = new Tropo();
      $startRecording = new StartRecording(AudioFormat::$mp3, "POST", "111111", "http://192.168.26.203/tropo-webapi-php/upload_file.php", "root", "1234", "plain", "mailto:you@yourmail.com", false);
      $tropo->startRecording($startRecording);
      $say = new Say("I am now recording!", null, null, null, null, "say");
      $tropo->say($say);
      $tropo->stopRecording();
      $this->assertEquals(sprintf($tropo), '{"tropo":[{"startRecording":{"format":"audio/mp3","method":"POST","password":"111111","url":"http://192.168.26.203/tropo-webapi-php/upload_file.php","username":"root","transcriptionID":"1234","transcriptionEmailFormat":"plain","transcriptionOutURI":"mailto:you@yourmail.com","asyncUpload":false}},{"say":[{"value":"I am now recording!","name":"say"}]},{"stopRecording":"null"}]}');
    }
    
}
?>