<?php
/**
* This file contains PHP classes that can be used to interact with the Tropo WebAPI/
* @see https://www.tropo.com/docs/webapi/
*
* @copyright 2010 Mark J. Headd (http://www.voiceingov.org)
* @package TropoPHP
* @author Mark Headd
* @author Adam Kalsey
*/

/**
* The main Tropo WebAPI class.
* The methods on this class can be used to invoke specifc Tropo actions.
* @package TropoPHP
* @see https://www.tropo.com/docs/webapi/tropo.htm
*
*/
include 'tropo-rest.class.php';

class Tropo extends BaseClass {

  /**
  * The container for JSON actions.
  *
  * @var array
  * @access private
  */
  public $tropo;

  /**
  * The TTS voice to use when rendering content.
  *
  * @var string
  * @access private
  */
  private $_voice;

  /**
  * The language to use when rendering content.
  *
  * @var string
  * @access private
  */
  private $_language;

  /**
  * Class constructor for the Tropo class.
  * @access private
  */
  public function __construct() {
    $this->tropo = array();
  }

  /**
  * Set a default voice for use with all Text To Speech.
  *
  * Tropo's text to speech engine can pronounce your text with
  * a variety of voices in different languages. All elements where
  * you can create text to speech (TTS) accept a voice parameter.
  * Tropo's default is "Allison" but you can set a default for this
  * script here.
  *
  * @param string $voice
  */
  public function setVoice($voice) {
    $this->_voice = $voice;
  }

  /**
  * Set a default language to use in speech recognition.
  *
  * When recognizing spoken input, Tropo allows you to set a language
  * to let the platform know which language is being spoken and which
  * recognizer to use. The default is en-us (US English), but you can
  * set a different default to be used in your application here.
  *
  * @param string $language
  */
  public function setLanguage($language) {
    $this->_language = $language;
  }

  /**
  * Sends a prompt to the user and optionally waits for a response.
  *
  * The ask method allows for collecting input using either speech
  * recognition or DTMF (also known as Touch Tone). You can either
  * pass in a fully-formed Ask object or a string to use as the
  * prompt and an array of parameters.
  *
  * @param string|Ask $ask
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/ask.htm
  */
  public function ask($ask, Array $params=NULL) {
    if ($ask instanceof Ask) {

      if(null === $ask->getChoices()) {
        throw new Exception("Missing required property: 'choices'");
      }
      if(null === $ask->getSay()) {
        throw new Exception("Missing required property: 'say'");
      }

    } elseif (is_string($ask) && ($ask !== '')) {

      if (isset($params) && is_array($params)) {

        if (array_key_exists('choices', $params)) {

          if ($params["choices"] instanceof Choices) {

            $choices = $params["choices"];

          } elseif (is_string($params['choices']) && ($params['choices'] !== '')) {

            $params["mode"] = isset($params["mode"]) ? $params["mode"] : null;
            $params["terminator"] = isset($params["terminator"]) ? $params["terminator"] : null;
            $choices = new Choices($params["choices"], $params["mode"], $params["terminator"]);

          } else {

            throw new Exception("'choices' must be is a string or an instance of Choices.");

          }
        } else {

          throw new Exception("Missing required property: 'choices'");

        }

        $p = array('event','voice','attempts', 'bargein', 'minConfidence', 'name', 'required', 'timeout', 'allowSignals', 'recognizer', 'interdigitTimeout', 'sensitivity', 'speechCompleteTimeout', 'speechIncompleteTimeout', 'promptLogSecurity', 'asrLogSecurity', 'maskTemplate');
        foreach ($p as $option) {
          $$option = null;
          if (array_key_exists($option, $params)) {
            $$option = $params[$option];
          }
        }
        if (is_array($event)) {
          foreach ($event as $e => $val){
            $say[] = new Say($val, null, $e);
          }
        }
        $say[] = new Say($ask);
        $ask = new Ask($attempts, $bargein, $choices, $minConfidence, $name, $required, $say, $timeout, $voice, $allowSignals, $recognizer, $interdigitTimeout, $sensitivity, $speechCompleteTimeout, $speechIncompleteTimeout, $promptLogSecurity, $asrLogSecurity, $maskTemplate);

      } else {

        throw new Exception("When Argument 1 passed to Tropo::ask() is a string, argument 2 passed to Tropo::ask() must be of the type array.");

      }

    } else {

      throw new Exception("Argument 1 passed to Tropo::ask() must be a string or an instance of Ask.");

    }
    $this->ask = sprintf('%s', $ask);
  }

  /**
  * Places a call or sends an an IM, Twitter, or SMS message. To start a call, use the Session API to tell Tropo to launch your code.
  *
  * @param string|Call $call
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/call.htm
  */
  public function call($call, Array $params=NULL) {
    if ($call instanceof Call) {

      if(null === $call->getTo()) {
        throw new Exception("Missing required property: 'to'");
      }
      if(null === $call->getName()) {
        throw new Exception("Missing required property: 'name'");
      }

    } elseif (is_string($call) && ($call !== '')) {

      if (isset($params) && is_array($params)) {

        if (array_key_exists('name', $params)) {
          if (is_string($params["name"]) && ($params["name"] !=='')) {
            $name = $params["name"];
          } else {
            throw new Exception("'name' must be is a string.");
          }
        } else {
          throw new Exception("Missing required property: 'name'");
        }

        if (array_key_exists('to', $params)) {
          if (is_array($params["to"])) {
            $to[] = $call;
            foreach ($params["to"] as $value) {
              if (is_string($value) && ($value !== '')) {
                $to[] = $value;
              }
            }
          } elseif (is_string($params["to"]) && ($params["to"] !== '')) {
            $to[] = $call;
            $to[] = $params["to"];
          } else {
            $to = $call;
          }
        } else {
          $to = $call;
        }

        $p = array('from', 'network', 'channel', 'answerOnMedia', 'timeout', 'headers', 'allowSignals', 'machineDetection', 'voice', 'required', 'callbackUrl', 'promptLogSecurity', 'label');
        foreach ($p as $option) {
          $$option = null;
          if (array_key_exists($option, $params)) {
            $$option = $params[$option];
          }
        }
        $call = new Call($to, $from, $network, $channel, $answerOnMedia, $timeout, $headers, null, $allowSignals, $machineDetection, $voice, $name, $required, $callbackUrl, $promptLogSecurity, $label);

      } else {

        throw new Exception("When Argument 1 passed to Tropo::call() is a string, argument 2 passed to Tropo::call() must be of the type array.");

      }
    } else {

      throw new Exception("Argument 1 passed to Tropo::call() must be a string or an instance of Call.");

    }
    $this->call = sprintf('%s', $call);
  }

  /**
  * This object allows multiple lines in separate sessions to be conferenced together so that the parties on each line can talk to each other simultaneously.
  * This is a voice channel only feature.
  *
  * @param string|Conference $conference
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/conference.htm
  */
  public function conference($conference, Array $params=NULL) {
    if ($conference instanceof Conference) {

      if(null === $conference->getId()) {
        throw new Exception("Missing required property: 'id'");
      }
      if(null === $conference->getName()) {
        throw new Exception("Missing required property: 'name'");
      }

    } elseif (is_string($conference) && ($conference !== '')) {

      if (isset($params) && is_array($params)) {

        if (array_key_exists('name', $params)) {
          if (is_string($params["name"]) && ($params["name"] !=='')) {
            $name = $params["name"];
          } else {
            throw new Exception("'name' must be is a string.");
          }
        } else {
          throw new Exception("Missing required property: 'name'");
        }

        $p = array('name', 'mute', 'on', 'playTones', 'required', 'terminator', 'allowSignals', 'interdigitTimeout', 'joinPrompt', 'leavePrompt', 'voice', 'promptLogSecurity');
        foreach ($p as $option) {
          $$option = null;
          if (array_key_exists($option, $params)) {
            $$option = $params[$option];
          }
        }
        $id = $conference;
        $conference = new Conference($name, $id, $mute, $on, $playTones, $required, $terminator, $allowSignals, $interdigitTimeout, $joinPrompt, $leavePrompt, $voice, $promptLogSecurity);
      } else {

        throw new Exception("When Argument 1 passed to Tropo::conference() is a string, argument 2 passed to Tropo::conference() must be of the type array.");

      }
    } else {

      throw new Exception("Argument 1 passed to Tropo::conference() must be a string or an instance of Conference.");

    }
    $this->conference = sprintf('%s', $conference);
  }

  /**
  * This function instructs Tropo to "hang-up" or disconnect the session associated with the current session.
  * @see https://www.tropo.com/docs/webapi/hangup.htm
  */
  public function hangup() {
    $hangup = new Hangup();
    $this->hangup = sprintf('%s', $hangup);
  }

  /**
  * A shortcut method to create a session, say something, and hang up, all in one step. This is particularly useful for sending out a quick SMS or IM.
  *
  * @param string|Message $message
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/message.htm
  */
  public function message($message, Array $params=null) {

    if ($message instanceof Message) {
      if(null === $message->getSay()) {
        throw new Exception("Missing required property: 'say'");
      }
      if(null === $message->getTo()) {
        throw new Exception("Missing required property: 'to'");
      }
      if(null === $message->getName()) {
        throw new Exception("Missing required property: 'name'");
      }
    } elseif (is_string($message) && ($message!=='')) {

      if (isset($params) && is_array($params)) {
        if (array_key_exists('to', $params)) {
          if (is_array($params["to"])) {
            foreach ($params["to"] as $value) {
              if (is_string($value) && ($value !== '')) {
                $to[] = $value;
              } else {
                throw new Exception("'to' must be is a string or an array of string.");
              }
            }
          } elseif (is_string($params["to"]) && ($params["to"]!=='')) {
            $to = $params["to"];
          } else {
            throw new Exception("'to' must be is a string or an array of string.");
          }
        } else {
          throw new Exception("Missing required property: 'to'");
        }

        if (array_key_exists('name', $params)) {
          if (is_string($params["name"]) && ($params["name"] !=='')) {
            $name = $params["name"];
          } else {
            throw new Exception("'name' must be is a string.");
          }
        } else {
          throw new Exception("Missing required property: 'name'");
        }

        if (array_key_exists('say', $params)) {
          if (is_array($params["say"])) {
            $say[] = new Say($message);
            foreach ($params["say"] as $value) {
              if (is_string($value) && ($value !== '')) {
                $say[] = new Say($value);
              }
            }
          } elseif (is_string($params["say"]) && ($params["say"] !== '')) {
            $say[] = new Say($message);
            $say[] = new Say($params["say"]);
          } else {
            $say = new Say($message);
          }
        } else {
          $say = new Say($message);
        }
        
        $p = array('channel', 'network', 'from', 'voice', 'timeout', 'answerOnMedia','headers','required','promptLogSecurity');
        foreach ($p as $option) {
          $$option = null;
          if (is_array($params) && array_key_exists($option, $params)) {
            $$option = $params[$option];
          }
        }
        $message = new Message($say, $to, $name, $channel, $network, $from, $voice, $timeout, $answerOnMedia, $headers, $required, $promptLogSecurity);
      } else {
        throw new Exception("When Argument 1 passed to Tropo::message() is a string, argument 2 passed to Tropo::message() must be of the type array.");
      }
    } else {
      throw new Exception("Argument 1 passed to Tropo::message() must be a string or an instance of Message.");
    }

    $this->message = sprintf('%s', $message);
  }

  /**
  * Adds an event callback so that your application may be notified when a particular event occurs.
  * Possible events are: "continue", "error", "incomplete" and "hangup".
  *
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/on.htm
  */
  public function on($on) {
    if (!is_object($on))	{
      if (is_array($on)) {
        $params = $on;
        if (!$params["event"]) {
          throw new Exception("Missing required property: 'event'");
        }
        if (!$params["say"]) {
          throw new Exception("Missing required property: 'say'");
        }
        if (!is_object($params["say"])) {
          throw new Exception("Property 'say' must be a Say object");
        }
        $next = (array_key_exists('next', $params)) ? $params["next"] : null;
        $on = new On($params["event"], $next, $params["say"]);
      }
    } else {
      if(!($on->getEvent())) {
        throw new Exception("Missing required property: 'event'");
      }
      if(!($on->getSay())) {
        throw new Exception("Missing required property: 'say'");
      }
    }
    $this->on = array(sprintf('%s', $on));
  }

  /**
  * Plays a prompt (audio file or text to speech) and optionally waits for a response from the caller that is recorded.
  * If collected, responses may be in the form of DTMF or speech recognition using a simple grammar format defined below.
  * The record funtion is really an alias of the prompt function, but one which forces the record option to true regardless of how it is (or is not) initially set.
  * At the conclusion of the recording, the audio file may be automatically sent to an external server via FTP or an HTTP POST/Multipart Form.
  * If specified, the audio file may also be transcribed and the text returned to you via an email address or HTTP POST/Multipart Form.
  *
  * @param array|Record $record
  * @see https://www.tropo.com/docs/webapi/record.htm
  */
  public function record($record) {
    if ($record instanceof Record) {

      if(null === $record->getUrl()) {
        throw new Exception("Missing required property: 'url'");
      }
      if(null === $record->getName()) {
        throw new Exception("Missing required property: 'name'");
      }

    } elseif (is_array($record)) {

      $params = $record;
      $p = array('voice', 'emailFormat', 'transcription', 'terminator');
      foreach ($p as $option) {
        $params[$option] = array_key_exists($option, $params) ? $params[$option] : null;
      }
      $choices = isset($params["choices"])
        ? new Choices(null, null, $params["choices"]) 
        : null;
      $choices = isset($params["terminator"])
        ? new Choices(null, null, $params["terminator"]) 
        : $choices;
      if (!isset($params['voice'])) {
        $params['voice'] = $this->_voice;
      }
      if (is_array($params['transcription'])) {
        $p = array('url', 'id', 'emailFormat');
        foreach ($p as $option) {
          $$option = null;
          if (!is_array($params["transcription"]) || !array_key_exists($option, $params["transcription"])) {
            $params["transcription"][$option] = null;
          }
        }
        $transcription = new Transcription($params["transcription"]["url"],$params["transcription"]["id"],$params["transcription"]["emailFormat"]);
      } else {
        $transcription = $params["transcription"];
      }
      $p = array('attempts', 'allowSignals', 'bargein', 'beep', 'format', 'maxTime', 'maxSilence', 'method', 'password', 'required', 'timeout', 'username', 'url', 'voice', 'minConfidence', 'interdigitTimeout', 'asyncUpload', 'name', 'promptLogSecurity');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      if (array_key_exists('say', $params)) {
        if (is_string($params["say"]) && ($params["say"] !== '')) {

          $say[] = new Say($params["say"]);

        }
      }
      if (array_key_exists('event', $params)) {

        if (is_array($params["event"])) {
          foreach ($params["event"] as $e => $value) {
            $say[] = new Say($value, null, $e);
          }
        }
      }
      if (!isset($say)) {
        $say = null;
      }
      $record = new Record($attempts, $allowSignals, $bargein, $beep, $choices, $format, $maxSilence, $maxTime, $method, $password, $required, $say, $timeout, $transcription, $username, $url, $voice, $minConfidence, $interdigitTimeout, $asyncUpload, $name, $promptLogSecurity);
      
    } else {

      throw new Exception("Argument 1 passed to Tropo::record() must be a array or an instance of Record.");

    }
    $this->record = sprintf('%s', $record);
  }

  /**
  * The redirect function forwards an incoming call to another destination / phone number before answering it.
  * The redirect function must be called before answer is called; redirect expects that a call be in the ringing or answering state.
  * Use transfer when working with active answered calls.
  *
  * @param string|Redirect $redirect
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/redirect.htm
  */
  public function redirect($redirect, Array $params=NULL) {
    if(!is_object($redirect)) {
      $to = isset($params["to"]) ? $params["to"]: null;
      $from = isset($params["from"]) ? $params["from"] : null;
      $redirect = new Redirect($to, $from);
    }
    $this->redirect = sprintf('%s', $redirect);
  }

  /**
  * Allows Tropo applications to reject incoming sessions before they are answered.
  * For example, an application could inspect the callerID variable to determine if the user is known, and then use the reject call accordingly.
  *
  * @see https://www.tropo.com/docs/webapi/reject.htm
  *
  */
  public function reject() {
    $reject = new Reject();
    $this->reject = sprintf('%s', $reject);
  }

  /**
  * When the current session is a voice channel this key will either play a message or an audio file from a URL.
  * In the case of an text channel it will send the text back to the user via i nstant messaging or SMS.
  *
  * @param string|Say $say
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/say.htm
  */
  public function say($say, Array $params=NULL) {
    if(!is_object($say)) {
      if(!$say) {
        throw new Exception("Missing required property: 'value'");
      }
      $p = array('as', 'event','voice', 'allowSignals', 'name', 'required', 'promptLogSecurity');
      $value = $say;
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      if(!$name) {
        throw new Exception("Missing required property: 'name'");
      }
      $voice = isset($voice) ? $voice : $this->_voice;
      $event = null;
      $say = new Say($value, $as, $event, $voice, $allowSignals, $name, $required, $promptLogSecurity);
    } else {
      $say->setEvent(null);
      if(!($say->getValue())) {
        throw new Exception("Missing required property: 'value'");
      }
      if(!($say->getName())) {
        throw new Exception("Missing required property: 'name'");
      }
    }
    $this->say = array(sprintf('%s', $say));
  }

  /**
  * Allows Tropo applications to begin recording the current session.
  * The resulting recording may then be sent via FTP or an HTTP POST/Multipart Form.
  *
  * @param array|StartRecording $startRecording
  * @see https://www.tropo.com/docs/webapi/startrecording.htm
  */
  public function startRecording($startRecording) {
    if(!is_object($startRecording) && is_array($startRecording)) {
      $params = $startRecording;
      $p = array('format', 'method', 'password', 'url', 'username', 'transcriptionID', 'transcriptionEmailFormat', 'transcriptionOutURI');
      foreach ($p as $option) {
        $$option = null;
        if (is_array($params) && array_key_exists($option, $params)) {
          $$option = $params[$option];
        }
      }
      $startRecording = new StartRecording($format, $method, $password, $url, $username, $transcriptionID, $transcriptionEmailFormat, $transcriptionOutURI);
    }
    $this->startRecording = sprintf('%s', $startRecording);
  }

  /**
  * Stops a previously started recording.
  *
  * @see https://www.tropo.com/docs/webapi/stoprecording.htm
  */
  public function stopRecording() {
    $stopRecording = new stopRecording();
    $this->stopRecording = sprintf('%s', $stopRecording);
  }

  /**
  * Transfers an already answered call to another destination / phone number.
  * Call may be transferred to another phone number or SIP address, which is set through the "to" parameter and is in URL format.
  *
  * @param string|Transfer $transfer
  * @param array $params
  * @see https://www.tropo.com/docs/webapi/transfer.htm
  */
  public function transfer($transfer, Array $params=NULL) {
    if ($transfer instanceof Transfer) {//$transfer is an instance of Transfer

      if(null === $transfer->getTo()) {
        throw new Exception("Missing required property: 'to'");
      }
      if(null === $transfer->getName()) {
        throw new Exception("Missing required property: 'name'");
      }

    } elseif (is_array($transfer) || (is_string($transfer) && ($transfer !== ''))) {//$transfer is a non-empty string or a non-empty string of array

      if (is_array($transfer)) {
        $to = null;
        foreach ($transfer as $value) {
          if (is_string($value) && ($value !== '')) {
            $to[] = $value;
          }
        }
        if (null === $to) {

          throw new Exception("Argument 1 passed to Tropo::transfer() must be a string or a string of array or an instance of Transfer.");

        }
      } else {
        $to = $transfer;
      }

      if (isset($params) && is_array($params)) {

        if (array_key_exists('name', $params)) {
          if (is_string($params["name"]) && ($params["name"] !=='')) {
            $name = $params["name"];
          } else {
            throw new Exception("'name' must be is a string.");
          }
        } else {
          throw new Exception("Missing required property: 'name'");
        }

        $choices = null;
        if (array_key_exists('choices', $params)) {

          if ($params["choices"] instanceof Choices) {
            $choices = $params["choices"];
          } elseif (is_string($params["choices"]) && ($params["choices"] !== '')) {
            $choices = new Choices(null, null, $params["choices"]);
          } else {
            $choices = null;
          }
        }

        if (array_key_exists('terminator', $params)) {
          if (is_string($params["terminator"]) && ($params["terminator"] !== '')) {
            $choices = new Choices(null, null, $params["terminator"]);
          }
        }
        
        $p = array('answerOnMedia', 'ringRepeat', 'timeout', 'from', 'allowSignals', 'headers', 'machineDetection', 'voice', 'required', 'interdigitTimeout', 'playTones', 'callbackUrl', 'promptLogSecurity', 'label');
        foreach ($p as $option) {
          $$option = null;
          if (array_key_exists($option, $params)) {
            $$option = $params[$option];
          }
        }
        $on = null;
        if (array_key_exists('on', $params)) {
          if ($params['on'] instanceof On) {
            if (is_string($params['on']->getEvent()) && ((strtolower($params['on']->getEvent()) == 'ring') || (strtolower($params['on']->getEvent()) == 'connect'))) {
              $on = $params['on'];
            } else {
              throw new TropoException("The only event allowed on transfer is 'ring' or 'connect'");
            }
          } elseif (is_array($params['on'])) {
            foreach ($params['on'] as $value) {
              if ($value instanceof On) {
                if (is_string($value->getEvent()) && ((strtolower($value->getEvent()) == 'ring') || (strtolower($value->getEvent()) == 'connect'))) {
                  $on[] = $value;
                } else {
                  throw new TropoException("The only event allowed on transfer is 'ring' or 'connect'");
                }
              }
            }
          }
        }
        $transfer = new Transfer($to, $answerOnMedia, $choices, $from, $ringRepeat, $timeout, $on, $allowSignals, $headers, $machineDetection, $voice, $name, $required, $interdigitTimeout, $playTones, $callbackUrl, $promptLogSecurity, $label);
      } else {

        throw new Exception("When Argument 1 passed to Tropo::transfer() is a string or a string of array, argument 2 passed to Tropo::transfer() must be of the type array.");

      }

    } else {

      throw new Exception("Argument 1 passed to Tropo::transfer() must be a string or a string of array or an instance of Transfer.");

    }
    $this->transfer = sprintf('%s', $transfer);
  }
  
  /**
  * Makes the Tropo sleep an active call in milliseconds
  *
  * @param Interger $milliseconds
  * @param String or Array $allowSignals
  * @see https://www.tropo.com/docs/webapi/wait.htm
  */
  public function wait($wait) {
     if (!is_object($wait) && is_array($wait)){
        $params = $wait;
        $signal = isset($params['allowSignals']) ? $params['allowSignals'] : null;
        $wait = new Wait($params["milliseconds"], $signal);
    }
    $this->wait = sprintf('%s', $wait);
    
  }

  /**
  * Launches a new session with the Tropo Session API.
  * (Pass through to SessionAPI class.)
  *
  * @param string $token Your outbound session token from Tropo
  * @param array $params An array of key value pairs that will be added as query string parameters
  * @return bool True if the session was launched successfully
  */
  public function createSession($token, Array $params=NULL) {
    try {
      $session = new SessionAPI();
      $result = $session->createSession($token, $params);
      return $result;
    }
    // If an exception occurs, wrap it in a TropoException and rethrow.
    catch (Exception $ex) {
      throw new TropoException($ex->getMessage(), $ex->getCode());
    }
  }

  public function sendEvent($session_id, $value) {
    try {
      $event = new EventAPI();
      $result = $event->sendEvent($session_id, $value);
      return $result;
    }
    catch (Exception $ex) {
      throw new TropoException($ex->getMessage(), $ex->getCode());
    }
  }

  /**
  * Creates a new Tropo Application
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param array $params
  * @return string JSON
  */
  public function createApplication($userid, $password, Array $params) {
    $p = array('href', 'name', 'voiceUrl', 'messagingUrl', 'platform', 'partition');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $password);
      $result = $provision->createApplication($href, $name, $voiceUrl, $messagingUrl, $platform, $partition);
      return $result;
    }
    // If an exception occurs, wrap it in a TropoException and rethrow.
    catch (Exception $ex) {
      throw new TropoException($ex->getMessage(), $ex->getCode());
    }
  }

  /**
  * Add/Update an address (phone number, IM address or token) for an existing Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @param array $params
  * @return string JSON
  */
  public function updateApplicationAddress($userid, $passwd, $applicationID, Array $params) {
    $p = array('type', 'prefix', 'number', 'city', 'state', 'channel', 'username', 'password', 'token');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $passwd);
      $result = $provision->updateApplicationAddress($applicationID, $type, $prefix, $number, $city, $state, $channel, $username, $password, $token);
      return $result;
    }
    // If an exception occurs, wrap it in a TropoException and rethrow.
    catch (Exception $ex) {
      throw new TropoException($ex->getMessage(), $ex->getCode());
    }
  }

  /**
  * Update a property (name, URL, platform, etc.) for an existing Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @param array $params
  * @return string JSON
  */
  public function updateApplicationProperty($userid, $password, $applicationID, Array $params) {
    $p = array('href', 'name', 'voiceUrl', 'messagingUrl', 'platform', 'partition');
    foreach ($p as $property) {
      $$property = null;
      if (is_array($params) && array_key_exists($property, $params)) {
        $$property = $params[$property];
      }
    }
    try {
      $provision = new ProvisioningAPI($userid, $password);
      $result = $provision->updateApplicationProperty($applicationID, $href, $name, $voiceUrl, $messagingUrl, $platform, $partition);
      return $result;
    }
    // If an exception occurs, wrap it in a TropoException and rethrow.
    catch (Exception $ex) {
      throw new TropoException($ex->getMessage(), $ex->getCode());
    }
  }

  /**
  * Delete an existing Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @return string JSON
  */
  public function deleteApplication($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->deleteApplication($applicationID);
  }

  /**
  * Delete an address for an existing Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @param string $number
  * @return string JSON
  */
  public function deleteApplicationAddress($userid, $password, $applicationID, $addresstype, $address) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->deleteApplicationAddress($applicationID, $addresstype, $address);
  }

  /**
  * View a list of Tropo applications.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @return string JSON
  */
  public function viewApplications($userid, $password) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewApplications();
  }

  /**
  * View the details of a specific Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @return string JSON
  */
  public function viewSpecificApplication($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewSpecificApplication($applicationID);
  }

  /**
  * View the addresses for a specific Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @param string $applicationID
  * @return string JSON
  */
  public function viewAddresses($userid, $password, $applicationID) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewAddresses($applicationID);
  }

  /**
  * View a list of available exchanges for assigning a number to a Tropo application.
  * (Pass through to ProvisioningAPI class).
  *
  * @param string $userid
  * @param string $password
  * @return string JSON
  */
  public function viewExchanges($userid, $password) {
    $provision = new ProvisioningAPI($userid, $password);
    return $provision->viewExchanges();
  }

  /**
  * Renders the Tropo object as JSON.
  *
  */
  public function renderJSON() {
    header('Content-type: application/json;charset=utf8');
    header('WebAPI-Lang-Ver: PHP V15.9.0 SNAPSHOT');
    echo $this;
  }

  /**
  * Allows undefined methods to be called.
  * This method is invloked by Tropo class methods to add action items to the Tropo array.
  *
  * @param string $name
  * @param mixed $value
  * @access private
  */
  public function __set($name, $value) {
    array_push($this->tropo, array($name => $value));
  }

  /**
  * Controls how JSON structure for the Tropo object is rendered.
  *
  * @return string
  * @access private
  */
  public function __toString() {
    // Remove voice and language so they do not appear in the rednered JSON.
    unset($this->_voice);
    unset($this->_language);

    // Call the unescapeJSON() method in the parent class.
    return parent::unescapeJSON(json_encode($this));
  }
}

/**
* Base class for Tropo class and indvidual Tropo action classes.
* Derived classes must implement both a constructor and __toString() function.
* @package TropoPHP_Support
* @abstract BaseClass
*/

abstract class BaseClass {

  /**
  * toString Function
  * @abstract __toString()
  */
  abstract public function __toString();

  /**
  * Allows derived classes to set Undeclared properties.
  *
  * @param mixed $attribute
  * @param mixed $value
  */
  public function __set($attribute, $value) {
    $this->$attribute= $value;
  }

  /**
  * Removes escape characters from a JSON string.
  *
  * @param string $json
  * @return string
  */
  public function unescapeJSON($json) {
    return str_replace(array('\"', "\"{", "}\"", '\\\\\/', '\\\\'), array('"', "{", "}", '/', '\\'), $json);
  }
}

/**
* Base class for empty actions.
* @package TropoPHP_Support
*
*/
class EmptyBaseClass {

  final public function __toString() {
    return json_encode(null);
  }
}



/**
* Action classes. Each specific object represents a specific Tropo action.
*
*/

/**
* Sends a prompt to the user and optionally waits for a response.
* @package TropoPHP_Support
*
*/
class Ask extends BaseClass {

  private $_attempts;
  private $_bargein;
  private $_choices;
  private $_minConfidence;
  private $_name;
  private $_required;
  private $_say;
  private $_timeout;
  private $_voice;
  private $_allowSignals;
  private $_recognizer;
  private $_interdigitTimeout;
  private $_sensitivity;
  private $_speechCompleteTimeout;
  private $_speechIncompleteTimeout;
  private $_promptLogSecurity;
  private $_asrLogSecurity;
  private $_maskTemplate;

  public function getChoices() {
    return $this->_choices;
  }

  public function getSay() {
    return $this->_say;
  }

  /**
  * Class constructor
  *
  * @param int $attempts
  * @param boolean $bargein
  * @param Choices $choices
  * @param float $minConfidence
  * @param string $name
  * @param boolean $required
  * @param Say $say
  * @param int $timeout
  * @param string $voice
  * @param string|array $allowSignals
  * @param integer $interdigitTimeout
  * @param integer $sensitivity 
  * @param float $speechCompleteTimeout
  * @param float $speechIncompleteTimeout
  */
  public function __construct($attempts=NULL, $bargein=NULL, Choices $choices, $minConfidence=NULL, $name=NULL, $required=NULL, $say, $timeout=NULL, $voice=NULL, $allowSignals=NULL, $recognizer=NULL, $interdigitTimeout=NULL, $sensitivity=NULL, $speechCompleteTimeout=NULL, $speechIncompleteTimeout=NULL, $promptLogSecurity=NULL, $asrLogSecurity=NULL, $maskTemplate=NULL) {
    if(!isset($choices)) {
      throw new Exception("Missing required property: 'choices'");
    }
    if(!isset($say)) {
      throw new Exception("Missing required property: 'say'");
    }
    $this->_attempts = $attempts;
    $this->_bargein = $bargein;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null ;
    $this->_minConfidence = $minConfidence;
    $this->_name = $name;
    $this->_required = $required;
    $this->_say = isset($say) ? $say : null;
    $this->_timeout = $timeout;
    $this->_voice = $voice;
    $this->_allowSignals = $allowSignals;
    $this->_recognizer = $recognizer;
    $this->_interdigitTimeout = $interdigitTimeout;
    $this->_sensitivity = $sensitivity;
    $this->_speechCompleteTimeout = $speechCompleteTimeout;
    $this->_speechIncompleteTimeout = $speechIncompleteTimeout;
    $this->_promptLogSecurity = $promptLogSecurity;
    $this->_asrLogSecurity = $asrLogSecurity;
    $this->_maskTemplate = $maskTemplate;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_attempts)) { $this->attempts = $this->_attempts; }
    if(isset($this->_bargein)) { $this->bargein = $this->_bargein; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_minConfidence)) { $this->minConfidence = $this->_minConfidence; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    if (is_array($this->_say)) {
      foreach ($this->_say as $k => $v) {
        $this->_say[$k] = sprintf('%s', $v);
      }
    }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_recognizer)) { $this->recognizer = $this->_recognizer; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    if(isset($this->_sensitivity)) { $this->sensitivity = $this->_sensitivity; }
    if(isset($this->_speechCompleteTimeout)) { $this->speechCompleteTimeout = $this->_speechCompleteTimeout; }
    if(isset($this->_speechIncompleteTimeout)) { $this->speechIncompleteTimeout = $this->_speechIncompleteTimeout; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    if(isset($this->_asrLogSecurity)) { $this->asrLogSecurity = $this->_asrLogSecurity; }
    if(isset($this->_maskTemplate)) { $this->maskTemplate = $this->_maskTemplate; }
    return $this->unescapeJSON(json_encode($this));
  }

  /**
  * Adds an additional Say to the Ask
  *
  * Used to add events such as a prompt to say on timeout or nomatch
  *
  * @param Say $say A say object
  */
  public function addEvent(Say $say) {
    $this->_say[] = $say;
  }
}

/**
* This object allows Tropo to make an outbound call. The call can be over voice or one
* of the text channels.
* @package TropoPHP_Support
*
*/
class Call extends BaseClass {

  private $_to;
  private $_from;
  private $_network;
  private $_channel;
  private $_answerOnMedia;
  private $_timeout;
  private $_headers;
  private $_allowSignals;
  private $_machineDetection;
  private $_voice;
  private $_name;
  private $_required;
  private $_callbackUrl;
  private $_promptLogSecurity;
  private $_label;

  public function getTo() {
    return $this->_to;
  }

  public function getName() {
    return $this->_name;
  }

  /**
  * Class constructor
  *
  * @param string $to
  * @param string $from
  * @param string $network
  * @param string $channel
  * @param boolean $answerOnMedia
  * @param int $timeout
  * @param array $headers
  * @param StartRecording $recording
  * @param string|array $allowSignals
  */
  public function __construct($to, $from=NULL, $network=NULL, $channel=NULL, $answerOnMedia=NULL, $timeout=NULL, Array $headers=NULL, StartRecording $recording=NULL, $allowSignals=NULL, $machineDetection=NULL, $voice=NULL, $name, $required=NULL, $callbackUrl=NULL, $promptLogSecurity=NULL, $label=NULL) {
    if(!isset($to)) {
      throw new Exception("Missing required property: 'to'");
    }
    if(!isset($name)) {
      throw new Exception("Missing required property: 'name'");
    }
    $this->_to = $to;
    $this->_from = $from;
    $this->_network = $network;
    $this->_channel = $channel;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_timeout = $timeout;
    $this->_headers = $headers;
    $this->_allowSignals = $allowSignals;
    $this->_machineDetection = $machineDetection;
    $this->_voice = $voice;
    $this->_name = $name;
    $this->_required = $required;
    $this->_callbackUrl = $callbackUrl;
    $this->_promptLogSecurity = $promptLogSecurity;
    $this->_label = $label;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_machineDetection)) {
      if(is_bool($this->_machineDetection)){
        $this->machineDetection = $this->_machineDetection; 
      }else{
        $this->machineDetection['introduction'] = $this->_machineDetection; 
        if(isset($this->_voice)){
          $this->machineDetection['voice'] = $this->_voice; 
        }
      }
    }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    $this->name = $this->_name;
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_callbackUrl)) { $this->callbackUrl = $this->_callbackUrl; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    if(isset($this->_label)) { $this->label = $this->_label; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Defines the input to be collected from the user.
* @package TropoPHP_Support
*/
class Choices extends BaseClass {

  private $_value;
  private $_mode;
  private $_terminator;

  public function getValue() {
    return $this->_value;
  }

  public function getMode() {
    return $this->_mode;
  }

  public function getTerminator() {
    return $this->_terminator;
  }

  /**
  * Class constructor
  *
  * @param string $value
  * @param string $mode
  * @param string $terminator
  */
  public function __construct($value=NULL, $mode=NULL, $terminator=NULL) {
    $this->_value = $value;
    $this->_mode = $mode;
    $this->_terminator = $terminator;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_value)){ $this->value = $this->_value; }
    if(isset($this->_mode)) { $this->mode = $this->_mode; }
    if(isset($this->_terminator)) { $this->terminator = $this->_terminator; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* This object allows multiple lines in separate sessions to be conferenced together so that
*   the parties on each line can talk to each other simultaneously.
*   This is a voice channel only feature.
*
* TODO: Conference object should support multiple event handlers (e.g. join and leave).
* @package TropoPHP_Support
*
*/
class Conference extends BaseClass {

  private $_id;
  private $_mute;
  private $_name;
  private $_playTones;
  private $_required;
  private $_terminator;
  private $_allowSignals;
  private $_interdigitTimeout;
  private $_joinPrompt;
  private $_leavePrompt;
  private $_promptLogSecurity;

  public function getName() {
    return $this->_name;
  }
  public function getId() {
    return $this->_id;
  }


  /**
  * Class constructor
  *
  * @param int $id
  * @param boolean $mute
  * @param string $name
  * @param On $on
  * @param boolean $playTones
  * @param boolean $required
  * @param string $terminator
  * @param string|array $allowSignals
  * @param int $interdigitTimeout
  */
  public function __construct($name, $id, $mute=NULL, On $on=NULL, $playTones=NULL, $required=NULL, $terminator=NULL, $allowSignals=NULL, $interdigitTimeout=NULL, $joinPrompt=NULL, $leavePrompt=NULL, $voice=NULL, $promptLogSecurity=NULL) {
    if(!isset($name)) {
      throw new Exception("Missing required property: 'name'");
    }
    if(!isset($id)) {
      throw new Exception("Missing required property: 'id'");
    }
    $this->_name = $name;
    $this->_id = (string) $id;
    $this->_mute = $mute;
    $this->_playTones = $playTones;
    $this->_required = $required;
    $this->_terminator = $terminator;
    $this->_allowSignals = $allowSignals;
    $this->_interdigitTimeout = $interdigitTimeout;
    $this->_joinPrompt = $joinPrompt;
    $this->_leavePrompt = $leavePrompt;
    $this->_promptLogSecurity = $promptLogSecurity;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->name = $this->_name;
    $this->id = $this->_id;
    if(isset($this->_mute)) { $this->mute = $this->_mute; }
    if(isset($this->_playTones)) { $this->playTones = $this->_playTones; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_terminator)) { $this->terminator = $this->_terminator; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    if(isset($this->_joinPrompt)) {
      if($this->_joinPrompt == true || $this->_joinPrompt == false){
        $this->joinPrompt = $this->_joinPrompt; 
      }else{
        $this->joinPrompt->value = $this->_joinPrompt; 
        if(isset($this->_voice)) { 
          $this->joinPrompt->voice = $this->_voice; 
        }
      }
    }
    if(isset($this->_leavePrompt)) {
      if($this->_leavePrompt == true || $this->_leavePrompt == false){
        $this->leavePrompt = $this->_leavePrompt; 
      }else{
        $this->leavePrompt->value = $this->_leavePrompt; 
        if(isset($this->_voice)) { 
          $this->leavePrompt->voice = $this->_voice; 
        }
      }
    }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* This function instructs Tropo to "hang-up" or disconnect the session associated with the current session.
* @package TropoPHP_Support
*
*/
class Hangup extends EmptyBaseClass { }

/**
* This function instructs Tropo to send a message.
* @package TropoPHP_Support
*
*/
class Message extends BaseClass {

  private $_say;
  private $_to;
  private $_channel;
  private $_network;
  private $_from;
  private $_voice;
  private $_timeout;
  private $_answerOnMedia;
  private $_headers;
  private $_name;
  private $_required;
  private $_promptLogSecurity;

  public function getSay() {
    return $this->_say;
  }

  public function getTo() {
    return $this->_to;
  }

  public function getName() {
    return $this->_name;
  }

  /**
  * Class constructor
  *
  * @param Say $say
  * @param string $to
  * @param string $channel
  * @param string $network
  * @param string $from
  * @param string $voice
  * @param integer $timeout
  * @param boolean $answerOnMedia
  * @param array $headers
  */
  public function __construct($say, $to, $name, $channel=null, $network=null, $from=null, $voice=null, $timeout=null, $answerOnMedia=null, Array $headers=null, $required=null, $promptLogSecurity=null) {
    if(!isset($say)) {
      throw new Exception("Missing required property: 'say'");
    }
    if(!isset($to)) {
      throw new Exception("Missing required property: 'to'");
    }
    if(!isset($name)) {
      throw new Exception("Missing required property: 'name'");
    }
    if ($say instanceof Say) {
      $this->_say = sprintf('%s', $say);
    } else {
      $this->_say = $say;
    }
    $this->_to = $to;
    $this->_name = $name;
    $this->_channel = $channel;
    $this->_network = $network;
    $this->_from = $from;
    $this->_voice = $voice;
    $this->_timeout = $timeout;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_headers = $headers;
    $this->_required = $required;
    $this->_promptLogSecurity = $promptLogSecurity;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->say = $this->_say;
    if (is_array($this->_say)) {
      foreach ($this->_say as $k => $v) {
        $this->_say[$k] = sprintf('%s', $v);
      }
    }
    $this->to = $this->_to;
    $this->name = $this->_name;
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(count($this->_required)) { $this->required = $this->_required; }
    if(count($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Adds an event callback so that your application may be notified when a particular event occurs.
* @package TropoPHP_Support
*
*/
class On extends BaseClass {

  private $_event;
  private $_next;
  private $_say;
  private $_ask;
  private $_post;

  public function getEvent() {
    return $this->_event;
  }

  public function getSay() {
    return $this->_say;
  }

  /**
  * Class constructor
  *
  * @param string $event
  * @param string $next
  * @param Say $say
  * @param string $voice
  */
  public function __construct($event, $next=NULL, Say $say=NULL, Ask $ask=NULL, $post=NULL) {
    if(!$event) {
      throw new Exception("Missing required property: 'event'");
    }
    $this->_event = $event;
    $this->_next = $next;
    $this->_say = isset($say) ? sprintf('%s', $say) : null ;
    $this->_ask = isset($ask) ? sprintf('%s', $ask) : null;
    $this->_post = $post;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_event)) { $this->event = $this->_event; }
    if(isset($this->_next)) { $this->next = $this->_next; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    if(isset($this->_ask)) { $this->ask = $this->_ask; }
    if(isset($this->_post)) { $this->post = $this->_post; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Plays a prompt (audio file or text to speech) and optionally waits for a response from the caller that is recorded.
* @package TropoPHP_Support
*
*/
class Record extends BaseClass {

  private $_attempts;
  private $_allowSignals;
  private $_bargein;
  private $_beep;
  private $_choices;
  private $_format;
  private $_maxSilence;
  private $_maxTime;
  private $_method;
  private $_password;
  private $_required;
  private $_say;
  private $_timeout;
  private $_transcription;
  private $_username;
  private $_url;
  private $_voice;
  private $_interdigitTimeout;
  private $_asyncUpload;
  private $_name;
  private $_promptLogSecurity;

  public function getUrl() {
    return $this->_url;
  }

  public function getName() {
    return $this->_name;
  }


  /**
  * Class constructor
  *
  * @param int $attempts
  * @param string|array $allowSignals
  * @param boolean $bargein
  * @param boolean $beep
  * @param Choices $choices
  * @param string $format
  * @param int $maxSilence
  * @param string $method
  * @param string $password
  * @param boolean $required
  * @param Say $say
  * @param int $timeout
  * @param string $username
  * @param string $url
  * @param string $voice
  * @param int $minConfidence
  * @param int $interdigitTimeout
  */
  public function __construct($attempts=NULL, $allowSignals=NULL, $bargein=NULL, $beep=NULL, Choices $choices=NULL, $format=NULL, $maxSilence=NULL, $maxTime=NULL, $method=NULL, $password=NULL, $required=NULL, $say=NULL, $timeout=NULL, Transcription $transcription=NULL, $username=NULL, $url, $voice=NULL, $minConfidence=NULL, $interdigitTimeout=NULL, $asyncUpload=NULL, $name, $promptLogSecurity=NULL) {
    if(!isset($url)) {
      throw new Exception("Missing required property: 'url'");
    }
    if(!isset($name)) {
      throw new Exception("Missing required property: 'name'");
    }
    $this->_attempts = $attempts;
    $this->_allowSignals = $allowSignals;
    $this->_bargein = $bargein;
    $this->_beep = $beep;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null;
    $this->_format = $format;
    $this->_maxSilence = $maxSilence;
    $this->_maxTime = $maxTime;
    $this->_method = $method;
    $this->_password = $password;
    $this->_required = $required;
    if ($say instanceof Say) {
      $this->_say = sprintf('%s', $say);
    } else {
      $this->_say = $say;
    }
    $this->_timeout = $timeout;
    $this->_transcription = isset($transcription) ? sprintf('%s', $transcription) : null;
    $this->_username = $username;
    $this->_url = $url;
    $this->_voice = $voice;
    $this->_interdigitTimeout = $interdigitTimeout;
    $this->_asyncUpload = $asyncUpload;
    $this->_name = $name;
    $this->_promptLogSecurity = $promptLogSecurity;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_attempts)) { $this->attempts = $this->_attempts; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_bargein)) { $this->bargein = $this->_bargein; }
    if(isset($this->_beep)) { $this->beep = $this->_beep; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_format)) { $this->format = $this->_format; }
    if(isset($this->_maxSilence)) { $this->maxSilence = $this->_maxSilence; }
    if(isset($this->_maxTime)) { $this->maxTime = $this->_maxTime; }
    if(isset($this->_method)) { $this->method = $this->_method; }
    if(isset($this->_password)) { $this->password = $this->_password; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_say)) { $this->say = $this->_say; }
    if (is_array($this->_say)) {
      foreach ($this->_say as $k => $v) {
        $this->_say[$k] = sprintf('%s', $v);
      }
    }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_transcription)) { $this->transcription = $this->_transcription; }
    if(isset($this->_username)) { $this->username = $this->_username; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    if(isset($this->_asyncUpload)) { $this->asyncUpload = $this->_asyncUpload; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* The redirect function forwards an incoming call to another destination / phone number before answering it.
* @package TropoPHP_Support
*
*/
class Redirect extends BaseClass {

  private $_to;
  private $_from;

  /**
  * Class constructor
  *
  * @param Endpoint $to
  * @param Endpoint $from
  */
  public function __construct($to=NULL, $from=NULL) {
    $this->_to = sprintf('%s', $to);
    $this->_from = isset($from) ? sprintf('%s', $from) : null;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_from)) { $this->from = $this->_from; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Allows Tropo applications to reject incoming sessions before they are answered.
* @package TropoPHP_Support
*
*/
class Reject extends EmptyBaseClass { }

/**
* Returned anytime a request is made to the Tropo Web API.
* @package TropoPHP
*
*/
class Result {

  private $_sessionId;
  private $_callId;
  private $_state;
  private $_sessionDuration;
  private $_sequence;
  private $_complete;
  private $_error;
  private $_actions;
  private $_name;
  private $_attempts;
  private $_disposition;
  private $_confidence;
  private $_interpretation;
  private $_concept;
  private $_userType;
  private $_utterance;
  private $_value;
  private $_transcription;

  /**
  * Class constructor
  *
  * @param string $json
  */
  public function __construct($json=NULL) {
    if(empty($json)) {
      $json = file_get_contents("php://input");
      // if $json is still empty, there was nothing in
      // the POST so throw an exception
      if(empty($json)) {
        throw new TropoException('No JSON available.');
      }
    }
    $result = json_decode($json);
    if (!is_object($result) || !property_exists($result, "result")) {
      throw new TropoException('Not a result object.');
    }
    $this->_sessionId = $result->result->sessionId;
    $this->_callId = $result->result->callId;
    $this->_state = $result->result->state;
    $this->_sessionDuration = $result->result->sessionDuration;
    $this->_sequence = $result->result->sequence;
    $this->_complete = $result->result->complete;
    $this->_error = $result->result->error;
    $this->_userType = $result->result->userType;
    $this->_actions = $result->result->actions;
    $this->_name = $result->result->actions->name;
    $this->_attempts = $result->result->actions->attempts;
    $this->_disposition = $result->result->actions->disposition;
    $this->_confidence = $result->result->actions->confidence;
    $this->_interpretation = $result->result->actions->interpretation;
    $this->_utterance = $result->result->actions->utterance;
    $this->_value = $result->result->actions->value;
    $this->_concept = isset($result->result->actions->concept) ? $result->result->actions->concept : null;
    $this->_transcription = isset($result->result->transcription) ? $result->result->transcription : null;
  }

  public function getSessionId() {
    return $this->_sessionId;
  }

  public function getCallId() {
    return $this->_callId;
  }

  public function getState() {
    return $this->_state;
  }

  public function getSessionDuration() {
    return $this->_sessionDuration;
  }

  public function getSequence() {
    return $this->_sequence;
  }

  public function isComplete() {
    return (bool) $this->_complete;
  }

  public function getError() {
    return $this->_error;
  }

  public function getUserType() {
    return $this->_userType;
  }
  
  public function getActions() {
    return $this->_actions;
  }

  public function getName() {
    return $this->_name;
  }

  public function getAttempts() {
    return $this->_attempts;
  }

  public function getDisposition() {
    return $this->_disposition;
  }

  public function getConfidence() {
    return $this->_confidence;
  }

  public function getInterpretation() {
    return $this->_interpretation;
  }

  public function getConcept() {
    return $this->_concept;
  }

  public function getUtterance() {
    return $this->_utterance;
  }

  public function getValue() {
    return $this->_value;
  }

  public function getTranscription() {
    return $this->_transcription;
  }
}

/**
* When the current session is a voice channel this key will either play a message or an audio file from a URL.
* In the case of an text channel it will send the text back to the user via instant messaging or SMS.
* @package TropoPHP_Support
*
*/
class Say extends BaseClass {

  private $_value;
  private $_as;
  private $_event;
  private $_voice;
  private $_allowSignals;
  private $_name;
  private $_required;
  private $_promptLogSecurity;

  public function getValue() {
    return $this->_value;
  }

  public function getName() {
    return $this->_name;
  }

  public function setEvent($event) {
    $this->_event = $event;
  }

  /**
  * Class constructor
  *
  * @param string $value
  * @param SayAs $as
  * @param string $event
  * @param string $voice
  * @param string|array $allowSignals
  */
  public function __construct($value, $as=NULL, $event=NULL, $voice=NULL, $allowSignals=NULL, $name=NULL, $required=NULL, $promptLogSecurity=NULL) {
    if(!$value) {
      throw new Exception("Missing required property: 'value'");
    }
    $this->_value = $value;
    $this->_as = $as;
    $this->_event = $event;
    $this->_voice = $voice;
    $this->_allowSignals = $allowSignals;
    $this->_name = $name;
    $this->_required = $required;
    $this->_promptLogSecurity = $promptLogSecurity;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_event)) { $this->event = $this->_event; }
    $this->value = $this->_value;
    if(isset($this->_as)) { $this->as = $this->_as; }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* The payload sent as an HTTP POST to the web application when a new session arrives.
*
* TODO: Consider using associative array for To and From.
* TODO: Need to break out headers into a more accessible data structure.
* @package TropoPHP
*/
class Session {

  private $_id;
  private $_accountId;
  private $_callId;
  private $_timestamp;
  private $_initialText;
  private $_to;
  private $_from;
  private $_headers;
  private $_parameters;
  private $_userType;

  /**
  * Class constructor
  *
  * @param string $json
  */
  public function __construct($json=NULL) {
    if(empty($json)) {
      $json = file_get_contents("php://input");
      // if $json is still empty, there was nothing in
      // the POST so throw exception
      if(empty($json)) {
        throw new TropoException('No JSON available.', 1);
      }
    }
    $session = json_decode($json);
    if (!is_object($session) || !property_exists($session, "session")) {
      throw new TropoException('Not a session object.', 2);
    }
    $this->_id = $session->session->id;
    $this->_accountId = $session->session->accountId;
    $this->_callId = $session->session->callId;
    $this->_timestamp = $session->session->timestamp;
    $this->_initialText = $session->session->initialText;
    $this->_userType = $session->session->userType;
    $this->_to = isset($session->session->to)
    ? array(
      "id" => $session->session->to->id,
      "e164Id" => $session->session->to->e164Id,
      "channel" => $session->session->to->channel,
      "name" => $session->session->to->name,
      "network" => $session->session->to->network
        )
      : array(
        "id" => null,
        "e164Id" => null,
        "channel" => null,
        "name" => null,
        "network" => null
        );
        $this->_from = isset($session->session->from->id)
        ? array(
          "id" => $session->session->from->id,
          "e164Id" => $session->session->from->e164Id,
          "channel" => $session->session->from->channel,
          "name" => $session->session->from->name,
          "network" => $session->session->from->network
            )
          : array(
            "id" => null,
            "e164Id" => null,
            "channel" => null,
            "name" => null,
            "network" => null
            );

            $this->_headers = isset($session->session->headers)
              ? self::setHeaders($session->session->headers)
              : array();
            $this->_parameters = property_exists($session->session, 'parameters') ? (Array) $session->session->parameters : null;
          }

          public function getId() {
            return $this->_id;
          }

          public function getAccountID() {
            return $this->_accountId;
          }

          public function getCallId() {
            return $this->_callId;
          }

          public function getTimeStamp() {
            return $this->_timestamp;
          }

          public function getInitialText() {
            return $this->_initialText;
          }

          public function getTo() {
            return $this->_to;
          }

          public function getFrom() {
            return $this->_from;
          }

          function getFromChannel() {
            return $this->_from['channel'];
          }

          function getFromNetwork() {
            return $this->_from['network'];
          }

          public function getHeaders() {
            return $this->_headers;
          }

          public function getUserType() {
            return $this->_userType;
          }

          /**
          * Returns the query string parameters for the session api
          *
          * If an argument is provided, a string containing the value of a
          * query string variable matching that string is returned or null
          * if there is no match. If no argument is argument is provided,
          * an array is returned with all query string variables or an empty
          * array if there are no query string variables.
          *
          * @param string $name A specific parameter to return
          * @return string|array $param
          */
          public function getParameters($name = null) {
            if (isset($name)) {
              if (!is_array($this->_parameters)) {
                // We've asked for a specific param, not there's no params set
                // return a null.
                return null;
              }
              if (isset($this->_parameters[$name])) {
                return $this->_parameters[$name];
              } else {
                return null;
              }
            } else {
              // If the parameters field doesn't exist or isn't an array
              // then return an empty array()
              if (!is_array($this->_parameters)) {
                return array();
              }
              return $this->_parameters;
            }
          }

          public function setHeaders($headers) {
            $formattedHeaders = new Headers();
            // headers don't exist on outboud calls
            // so only do this if there are headers
            if (is_object($headers)) {
              foreach($headers as $name => $value) {
                $formattedHeaders->$name = $value;
              }
            }
            return $formattedHeaders;
          }
        }

/**
* Allows Tropo applications to begin recording the current session.
* The resulting recording may then be sent via FTP or an HTTP POST/Multipart Form.
* @package TropoPHP_Support
*
*/
class StartRecording extends BaseClass {

  private $_name;
  private $_format;
  private $_method;
  private $_password;
  private $_url;
  private $_username;
  private $_transcriptionID;
  private $_transcriptionEmailFormat;
  private $_transcriptionOutURI;

  /**
  * Class constructor
  *
  * @param string $name
  * @param string $format
  * @param string $method
  * @param string $password
  * @param string $url
  * @param string $username
  * @param string $transcriptionID
  * @param string $transcriptionEmailFormat
  * @param string $transcriptionOutURI
  */
  public function __construct($format=NULL, $method=NULL, $password=NULL, $url=NULL, $username=NULL, $transcriptionID=NULL, $transcriptionEmailFormat=NULL, $transcriptionOutURI=NULL) {
    $this->_format = $format;
    $this->_method = $method;
    $this->_password = $password;
    $this->_url = $url;
    $this->_username = $username;
    $this->_transcriptionID = $transcriptionID;
    $this->_transcriptionEmailFormat = $transcriptionEmailFormat;
    $this->_transcriptionOutURI = $transcriptionOutURI;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_format)) { $this->format = $this->_format; }
    if(isset($this->_method)) { $this->method = $this->_method; }
    if(isset($this->_password)) { $this->password = $this->_password; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_username)) { $this->username = $this->_username; }
    if(isset($this->_transcriptionID)) { $this->transcriptionID = $this->_transcriptionID; }
    if(isset($this->_transcriptionEmailFormat)) { $this->transcriptionEmailFormat = $this->_transcriptionEmailFormat; }
    if(isset($this->_transcriptionOutURI)) { $this->transcriptionOutURI = $this->_transcriptionOutURI; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Stop an already started recording.
* @package TropoPHP_Support
*
*/
class StopRecording extends EmptyBaseClass { }

/**
* Transcribes spoken text.
* @package TropoPHP_Support
*
*/
class Transcription extends BaseClass {

  private $_url;
  private $_id;
  private $_emailFormat;

  /**
  * Class constructor
  *
  * @param string $url
  * @param string $id
  * @param string $emailFormat
  */
  public function __construct($url, $id=NULL, $emailFormat=NULL) {
    $this->_url = $url;
    $this->_id = $id;
    $this->_emailFormat = $emailFormat;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    if(isset($this->_id)) { $this->id = $this->_id; }
    if(isset($this->_url)) { $this->url = $this->_url; }
    if(isset($this->_emailFormat)) { $this->emailFormat = $this->_emailFormat; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Transfers an already answered call to another destination / phone number.
* @package TropoPHP_Support
*
*/
class Transfer extends BaseClass {

  private $_answerOnMedia;
  private $_choices;
  private $_from;
  private $_on;
  private $_ringRepeat;
  private $_timeout;
  private $_to;
  private $_allowSignals;
  private $_headers;
  private $_machineDetection;
  private $_voice;
  private $_name;
  private $_required;
  private $_interdigitTimeout;
  private $_playTones;
  private $_callbackUrl;
  private $_promptLogSecurity;
  private $_label;

  public function getTo() {
    return $this->_to;
  }
  public function getName() {
    return $this->_name;
  }

  /**
  * Class constructor
  *
  * @param string $to
  * @param boolean $answerOnMedia
  * @param Choices $choices
  * @param Endpoint $from
  * @param On $on
  * @param int $ringRepeat
  * @param int $timeout
  * @param string|array $allowSignals
  * @param array $headers
  */
  public function __construct($to, $answerOnMedia=NULL, Choices $choices=NULL, $from=NULL, $ringRepeat=NULL, $timeout=NULL, $on=NULL, $allowSignals=NULL, Array $headers=NULL, $machineDetection=NULL, $voice=NULL, $name, $required=NULL, $interdigitTimeout=NULL, $playTones=NULL, $callbackUrl=NULL, $promptLogSecurity=NULL, $label=NULL) {
    if(!isset($to)) {
      throw new Exception("Missing required property: 'to'");
    }
    if(!isset($name)) {
      throw new Exception("Missing required property: 'name'");
    }
    $this->_to = $to;
    $this->_answerOnMedia = $answerOnMedia;
    $this->_choices = isset($choices) ? sprintf('%s', $choices) : null;
    $this->_from = $from;
    $this->_ringRepeat = $ringRepeat;
    $this->_timeout = $timeout;
    $this->_on = null;
    if (isset($on)) {
      if ($on instanceof On) {
        $this->_on = sprintf('%s', $on);
      } else {
        $this->_on = $on;
      }
    }
    $this->_allowSignals = $allowSignals;
    $this->_headers = $headers;
    $this->_machineDetection = $machineDetection;
    $this->_voice = $voice;
    $this->_name = $name;
    $this->_required = $required;
    $this->_interdigitTimeout = $interdigitTimeout;
    $this->_playTones = $playTones;
    $this->_callbackUrl = $callbackUrl;
    $this->_promptLogSecurity = $promptLogSecurity;
    $this->_label = $label;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->to = $this->_to;
    if(isset($this->_answerOnMedia)) { $this->answerOnMedia = $this->_answerOnMedia; }
    if(isset($this->_choices)) { $this->choices = $this->_choices; }
    if(isset($this->_from)) { $this->from = $this->_from; }
    if(isset($this->_ringRepeat)) { $this->ringRepeat = $this->_ringRepeat; }
    if(isset($this->_timeout)) { $this->timeout = $this->_timeout; }
    if(isset($this->_on)) { $this->on = $this->_on; }
    if (is_array($this->_on)) {
      foreach ($this->_on as $k => $v) {
        $this->_on[$k] = sprintf('%s', $v);
      }
    }
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    if(count($this->_headers)) { $this->headers = $this->_headers; }
    if(isset($this->_machineDetection)) {
      if(is_bool($this->_machineDetection)){
        $this->machineDetection = $this->_machineDetection; 
      }else{
        $this->machineDetection['introduction'] = $this->_machineDetection; 
        if(isset($this->_voice)){
          $this->machineDetection['voice'] = $this->_voice; 
        }
      }
    }
    if(isset($this->_voice)) { $this->voice = $this->_voice; }
    $this->name = $this->_name;
    if(isset($this->_required)) { $this->required = $this->_required; }
    if(isset($this->_interdigitTimeout)) { $this->interdigitTimeout = $this->_interdigitTimeout; }
    if(isset($this->_playTones)) { $this->playTones = $this->_playTones; }
    if(isset($this->_callbackUrl)) { $this->callbackUrl = $this->_callbackUrl; }
    if(isset($this->_promptLogSecurity)) { $this->promptLogSecurity = $this->_promptLogSecurity; }
    if(isset($this->_label)) { $this->label = $this->_label; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Defines a time period to sleep in milliseconds
* @package TropoPHP_Support
*
*/
class Wait extends BaseClass {

  private $_milliseconds;
  private $_allowSignals;

  /**
  * Class constructor
  *
  * @param integer $milliseconds
  * @param string|array $allowSignals
  */
  public function __construct($milliseconds, $allowSignals=NULL) {
    $this->_milliseconds = $milliseconds;
    $this->_allowSignals = $allowSignals;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {
    $this->milliseconds = $this->_milliseconds; 
    if(isset($this->_allowSignals)) { $this->allowSignals = $this->_allowSignals; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* Defnies an endoint for transfer and redirects.
* @package TropoPHP_Support
*
*/
class Endpoint extends BaseClass {

  private $_id;
  private $_channel;
  private $_name = 'unknown';
  private $_network;

  /**
  * Class constructor
  *
  * @param string $id
  * @param string $channel
  * @param string $name
  * @param string $network
  */
  public function __construct($id, $channel=NULL, $name=NULL, $network=NULL) {

    $this->_id = $id;
    $this->_channel = $channel;
    $this->_name = $name;
    $this->_network = $network;
  }

  /**
  * Renders object in JSON format.
  *
  */
  public function __toString() {

    if(isset($this->_id)) { $this->id = $this->_id; }
    if(isset($this->_channel)) { $this->channel = $this->_channel; }
    if(isset($this->_name)) { $this->name = $this->_name; }
    if(isset($this->_network)) { $this->network = $this->_network; }
    return $this->unescapeJSON(json_encode($this));
  }
}

/**
* A helper class for wrapping exceptions. Can be modified for custom excpetion handling.
*
*/
class TropoException extends Exception { }

/**
* Date Helper class.
* @package TropoPHP_Support
*/
class Date {
  public static $monthDayYear = "mdy";
  public static $dayMonthYear = "dmy";
  public static $yearMonthDay = "ymd";
  public static $yearMonth = "ym";
  public static $monthYear = "my";
  public static $monthDay = "md";
  public static $year = "y";
  public static $month = "m";
  public static $day = "d";
}

/**
* Duration Helper class.
* @package TropoPHP_Support
*/
class Duration {
  public static $hoursMinutesSeconds = "hms";
  public static $hoursMinutes = "hm";
  public static $hours = "h";
  public static $minutes = "m";
  public static $seconds = "s";
}

/**
* Event Helper class.
* @package TropoPHP_Support
*/
class Event {

  public static $continue = 'continue';
  public static $incomplete = 'incomplete';
  public static $error = 'error';
  public static $hangup = 'hangup';
  public static $join = 'join';
  public static $leave = 'leave';
  public static $ring = 'ring';
}

/**
* Format Helper class.
* @package TropoPHP_Support
*/
class Format {
  public $date;
  public $duration;
  public static $ordinal = "ordinal";
  public static $digits = "digits";

  public function __construct($date=NULL, $duration=NULL) {
    $this->date = $date;
    $this->duration = $duration;
  }
}

/**
* SayAs Helper class.
* @package TropoPHP_Support
*/
class SayAs {
  public static $date = "DATE";
  public static $digits = "DIGITS";
  public static $number = "NUMBER";
}

/**
* Network Helper class.
* @package TropoPHP_Support
*/
class Network {
  public static $pstn = "PSTN";
  public static $voip = "VOIP";
  public static $aim = "AIM";
  public static $gtalk = "GTALK";
  public static $jabber = "JABBER";
  public static $msn = "MSN";
  public static $sms = "SMS";
  public static $yahoo = "YAHOO";
  public static $twitter = "TWITTER";
  public static $sip = "SIP";
}

/**
* Channel Helper class.
* @package TropoPHP_Support
*/
class Channel {
  public static $voice = "VOICE";
  public static $text = "TEXT";
}

/**
* AudioFormat Helper class.
* @package TropoPHP_Support
*/
class AudioFormat {
  public static $wav = "audio/wav";
  public static $mp3 = "audio/mp3";
  public static $au = "audio/au";
}

/**
* Voice Helper class.
* @package TropoPHP_Support
*/
class Voice {
  public static $Arabic_male_maged = "maged";
  public static $Arabic_male_tarik = "tarik";
  public static $Arabic_female = "laila";
  public static $Bahasa_female = "damayanti";
  public static $Basque_female = "miren";
  public static $Bulgarian_female = "daria";
  public static $Cantonese_female = "sin-ji";
  public static $Catalan_female = "montserrat";
  public static $Catalan_male = "jordi";
  public static $Czech_female_iveta = "iveta";
  public static $Czech_female_zuzana = "zuzana";
  public static $Danish_female = "sara";
  public static $Danish_male = "magnus";
  public static $Belgian_Dutch_female = "ellen";
  public static $Australian_English_female = "karen";
  public static $Australian_English_male = "lee";
  public static $Indian_English_female = "veena";
  public static $Irish_English_female = "moira";
  public static $Scottish_English_female = "fiona";
  public static $South_African_English_female = "tessa";
  public static $Uk_English_female_kate = "kate";
  public static $Uk_English_female_serena = "serena";
  public static $Uk_English_male_daniel = "daniel";
  public static $Uk_English_male_oliver = "oliver";
  public static $US_English_female_ava = "ava";
  public static $US_English_female_evelyn = "evelyn";
  public static $US_English_female_samantha = "samantha";
  public static $US_English_female_susan = "susan";
  public static $US_English_female_zoe = "zoe";
  public static $US_English_female_allison = "allison";
  public static $US_English_male_tom = "tom";
  public static $US_English_male_victor = "victor";
  public static $Finnish_female = "satu";
  public static $Finnish_male = "onni";
  public static $French_female_audrey = "audrey";
  public static $French_female_aurelie = "aurelie";
  public static $French_male = "thomas";
  public static $Canadian_French_female_amelie = "amelie";
  public static $Canadian_French_female_chantal = "chantal";
  public static $Canadian_French_male = "nicolas";
  public static $Galician_female = "carmela";
  public static $German_female_anna = "anna";
  public static $German_female_petra = "petra";
  public static $German_male_markus = "markus";
  public static $German_male_yannick = "yannick";
  public static $Greek_female = "melina";
  public static $Greek_male = "nikos";
  public static $Hebrew_female = "carmit";
  public static $Hindi_female = "lekha";
  public static $Hungarian_female = "mariska";
  public static $Italian_female_alice = "alice";
  public static $Italian_female_federica = "federica";
  public static $Italian_male = "luca";
  public static $Italian_male_paola = "paola";
  public static $Japanese_female = "kyoko";
  public static $Japanese_male = "otoya";
  public static $Korean_female = "sora";
  public static $Mandarin_female = "tian-tian";
  public static $Taiwanese_Mandarin_female = "mei-jia";
  public static $Norwegian_female = "nora";
  public static $Norwegian_male = "henrik";
  public static $Polish_female_ewa = "ewa";
  public static $Polish_female_zosia = "zosia";
  public static $Polish_male = "krzysztof";
  public static $Portuguese_female_catarina = "catarina";
  public static $Portuguese_female_joana = "joana";
  public static $Portuguese_male = "joaquim";
  public static $Brazilian_Portuguese_female = "luciana";
  public static $Brazilian_Portuguese_male = "felipe";
  public static $Russian_female_katya = "katya";
  public static $Russian_female_milena = "milena";
  public static $Russian_male = "yuri";
  public static $Slovak_male = "laura";
  public static $Argentinean_Spanish_male = "diego";
  public static $Castilian_Spanish_female = "monica";
  public static $Castilian_Spanish_male = "jorge";
  public static $Colombian_Spanish_female = "soledad";
  public static $Colombian_Spanish_male = "carlos";
  public static $Mexican_Spanish_female_angelica = "angelica";
  public static $Mexican_Spanish_female_paulina = "paulina";
  public static $Mexican_Spanish_male = "juan";
  public static $Dutch_male = "xander";
  public static $Dutch_female = "claire";
  public static $Swedish_female_alva = "alva";
  public static $Swedish_female_klara = "klara";
  public static $Swedish_male = "oskar";
  public static $Thai_female = "kanya";
  public static $Turkish_female = "yelda";
  public static $Turkish_male = "cem";
  public static $Valencian_female = "empar";

}

/**
* Recognizer Helper class
* @package TropoPHP_Support
*
*/
class Recognizer {
  public static $Afrikaans = 'af-za';
  public static $Arabic = 'ar-ww';
  public static $Jordanian_Arabic = 'ar-jo';
  public static $Assamese = 'as-in';
  public static $Basque = 'eu-es';
  public static $Bengali = 'bn-bd';
  public static $Indian_Bengali = 'bn-bi';
  public static $Bhojpuri = 'bh-in';
  public static $Bulgarian = 'bg-bg';
  public static $Cantonese = 'cn-hk';
  public static $Catalan = 'ca-es';
  public static $Czech = 'cs-cz';
  public static $Danish = 'da-dk';
  public static $Dutch = 'nl-nl';
  public static $Belgian_Dutch = 'nl-be';
  public static $Australian_English = 'en-au';
  public static $Indian_English = 'en-in';
  public static $Singaporean_English = 'en-sg';
  public static $South_African_English = 'en-za';
  public static $UK_English = 'en-gb';
  public static $US_English = 'en-us';
  public static $Finnish = 'fi-fi';
  public static $French = 'fr-fr';
  public static $Belgian_French = 'fr-be';
  public static $Canadian_French = 'fr-ca';
  public static $Galician = 'gl-es';
  public static $German = 'de-de';
  public static $Austrian_German = 'de-at';
  public static $Swiss_German = 'de-ch';
  public static $Greek = 'el-gr';
  public static $Gujarati = 'gu-in';
  public static $Hebrew = 'he-il';
  public static $Hindi = 'hi-in';
  public static $Hungarian = 'hu-hu';
  public static $Icelandic = 'is-is';
  public static $Indonesian = 'id-id';
  public static $Italian = 'it-it';
  public static $Japanese = 'ja-jp';
  public static $Kannada = 'kn-in';
  public static $Korean = 'ko-kr';
  public static $Malay = 'ms-my';
  public static $Malayalam = 'ml-in';
  public static $Mandarin = 'zh-cn';
  public static $Taiwanese_Mandarin = 'zh-tw';
  public static $Marathi = 'mr-in';
  public static $Nepali = 'ne-np';
  public static $Norwegian = 'no-no';
  public static $Oriya = 'or-in';
  public static $Polish = 'pl-pl';
  public static $Portuguese = 'pt-pt';
  public static $Brazilian_Portuguese = 'pt-br';
  public static $Punjabi = 'pa-in';
  public static $Romanian = 'ro-ro';
  public static $Russian = 'ru-ru';
  public static $Serbian = 'sr-rs';
  public static $Slovak = 'sk-sk';
  public static $Slovenian = 'sl-si';
  public static $Spanish = 'es-es';
  public static $Argentinian_Spanish = 'es-ar';
  public static $Colombian_Spanish = 'es-co';
  public static $Mexican_Spanish = 'es-us';
  public static $US_Spanish = 'es-us';
  public static $Swedish = 'sv-se';
  public static $Tamil = 'ta-in';
  public static $Telugu = 'te-in';
  public static $Thai = 'th-th';
  public static $Turkish = 'tr-tr';
  public static $Ukrainian = 'uk-ua';
  public static $Indian_Urdu = 'ur-in';
  public static $Pakistani_Urdu = 'ur-pk';
  public static $Valencian = 'va-es';
  public static $Vietnamese = 'vi-vn';
  public static $Welsh = 'cy-gb';
}

/**
* SIP Headers Helper class.
* @package TropoPHP_Support
*/
class Headers {

  public function __set($name, $value) {
    if(!strstr($name, "-")) {
      $this->$name = $value;
    } else {
      $name = str_replace("-", "_", $name);
      $this->$name = $value;
    }
  }
}

?>
