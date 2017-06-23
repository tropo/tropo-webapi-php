<?php
use PHPUnit\Framework\TestCase;
require_once 'tropo.class.php';
 
class ResultTest extends PHPUnit_Framework_TestCase
{

    public function testResult() {
      $json = '{"result":{"sessionId":"26a5aa37ff9d14d11990a48865abb5d2","callId":"b11948c7073e815a768fd3f393cc92a6","state":"ANSWERED","sessionDuration":158,"sequence":1,"complete":true,"error":null,"calledid":"9992801029","actions":{"name":"foo","duration":149,"connectedDuration":145,"disposition":"SUCCESS","timestamp":"2017-06-23T02:45:22.849Z","calledid":"pengxli","userType":"HUMAN"}}}';
      $result = new Result($json);
      $this->assertEquals($result->getSessionId(), '26a5aa37ff9d14d11990a48865abb5d2');
      $this->assertEquals($result->getCallId(), 'b11948c7073e815a768fd3f393cc92a6');
      $this->assertEquals($result->getState(), 'ANSWERED');
      $this->assertEquals($result->getSessionDuration(), 158);
      $this->assertEquals($result->getSequence(), 1);
      $this->assertEquals($result->isComplete(), true);
      $this->assertEquals($result->getError(), null);
      $this->assertEquals($result->getCalledid(), '9992801029');
      $this->assertEquals($result->getUserType(), null);
      $actions = $result->getActions();
      $action = $actions;
      $this->assertEquals($result->getName($action), 'foo');
      $this->assertEquals($result->getAttempts($action), null);
      $this->assertEquals($result->getDisposition($action), 'SUCCESS');
      $this->assertEquals($result->getConfidence($action), null);
      $this->assertEquals($result->getInterpretation($action), null);
      $this->assertEquals($result->getUtterance($action), null);
      $this->assertEquals($result->getValue($action), null);
      $this->assertEquals($result->getConcept($action), null);
      $this->assertEquals($result->getXml($action), null);
      $this->assertEquals($result->getUploadStatus($action), null);
      $this->assertEquals($result->getDuration($action), 149);
      $this->assertEquals($result->getConnectedDuration($action), 145);
      $this->assertEquals($result->getUrl($action), null);
      $this->assertEquals($result->getActionUserType($action), 'HUMAN');
      $this->assertEquals($result->getTranscription($action), null);
    }

    public function testResult2() {
      $json = '{"result":{"sessionId":"349e753764123e3b90245162a0f7758a","callId":"925f666b73ea1e41211d624489deea17","state":"ANSWERED","sessionDuration":13,"sequence":1,"complete":true,"error":null,"calledid":"9992801029","actions":[{"name":"foo","attempts":1,"disposition":"SUCCESS","confidence":100,"interpretation":"1234","utterance":"1234","value":"1234","xml":"<?xml version=\\"1.0\\"?>\\r\\n<result grammar=\\"0@82ab4c9c.vxmlgrammar\\">\\r\\n    <interpretation grammar=\\"0@82ab4c9c.vxmlgrammar\\" confidence=\\"100\\">\\r\\n        \\r\\n      <input mode=\\"dtmf\\">1234<\\/input>\\r\\n    <\\/interpretation>\\r\\n<\\/result>\\r\\n"},{"name":"foo","attempts":1,"disposition":"SUCCESS","confidence":100,"interpretation":"1234","utterance":"1234","value":"1234","xml":"<?xml version=\\"1.0\\"?>\\r\\n<result grammar=\\"1@82ab4c9c.vxmlgrammar\\">\\r\\n    <interpretation grammar=\\"1@82ab4c9c.vxmlgrammar\\" confidence=\\"100\\">\\r\\n        \\r\\n      <input mode=\\"dtmf\\">1234<\\/input>\\r\\n    <\\/interpretation>\\r\\n<\\/result>\\r\\n"}]}}';
      $result = new Result($json);
      $this->assertEquals($result->getSessionId(), '349e753764123e3b90245162a0f7758a');
      $this->assertEquals($result->getCallId(), '925f666b73ea1e41211d624489deea17');
      $this->assertEquals($result->getState(), 'ANSWERED');
      $this->assertEquals($result->getSessionDuration(), 13);
      $this->assertEquals($result->getSequence(), 1);
      $this->assertEquals($result->isComplete(), true);
      $this->assertEquals($result->getError(), null);
      $this->assertEquals($result->getCalledid(), '9992801029');
      $this->assertEquals($result->getUserType(), null);
      $actions = $result->getActions();
      $action = $actions[0];
      $this->assertEquals($result->getName($action), 'foo');
      $this->assertEquals($result->getAttempts($action), 1);
      $this->assertEquals($result->getDisposition($action), 'SUCCESS');
      $this->assertEquals($result->getConfidence($action), 100);
      $this->assertEquals($result->getInterpretation($action), '1234');
      $this->assertEquals($result->getUtterance($action), '1234');
      $this->assertEquals($result->getValue($action), '1234');
      $this->assertEquals($result->getConcept($action), null);
      $this->assertEquals($result->getXml($action), "<?xml version=\"1.0\"?>\r\n<result grammar=\"0@82ab4c9c.vxmlgrammar\">\r\n    <interpretation grammar=\"0@82ab4c9c.vxmlgrammar\" confidence=\"100\">\r\n        \r\n      <input mode=\"dtmf\">1234</input>\r\n    </interpretation>\r\n</result>\r\n");
      $this->assertEquals($result->getUploadStatus($action), null);
      $this->assertEquals($result->getDuration($action), null);
      $this->assertEquals($result->getConnectedDuration($action), null);
      $this->assertEquals($result->getUrl($action), null);
      $this->assertEquals($result->getActionUserType($action), null);
      $this->assertEquals($result->getTranscription($action), null);
    }
}
?>