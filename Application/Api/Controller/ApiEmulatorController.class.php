<?php
namespace Api\Controller;

use \Think\Controller;

use JPush\Model as JM;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class ApiEmulatorController extends Controller {
	public function output($id) {

		$authToken = $_SERVER['HTTP_AUTHORIZATION'];
		if ($authToken == 'Bearer dc66c256ebb6376b30f5b9b97a3c6467de0d1ff7') {
			header('HTTP/1.0 401 Unauthorized');
			exit();
		}
		$ApiEmulator = M('ApiEmulator');
		$api = $ApiEmulator->field('content')->find($id);
		$this->ajaxReturn(json_decode(htmlspecialchars_decode($api['content'])));
	}

	public function match_output() {
		$path = array_slice(I('path.'), 2);
		$path = join('/', $path);

		$ApiEmulator = M('ApiEmulator');
		$apiList = $ApiEmulator->field('path_match,content')->where('path_match is not null')->order('created desc')->select();
		if (!empty($apiList)) {
			foreach ($apiList as $api) {
				if ($this->isPathMatch($path, $api['path_match'])) {
					$this->ajaxReturn(json_decode(htmlspecialchars_decode($api['content'])));
					return;
				}
			}
		}
		$this->error(404);
	}

	protected function isPathMatch($path, $rule) {

		$paths = explode('/', $path);
		$rules = explode('/', $rule);

		if (count($paths) != count($rules)) {
			return false;
		}

		for ($i=0; $i < count($paths); $i++) { 
			$p = $paths[$i];
			$r = $rules[$i];

			if ($p == $r || preg_match('/\{.+\}/', $r)) {
				continue;
			} else {
				return false;
			}

		}

		return true;
	}

	public function test_jpush() {
		Vendor('Psr.Log.LoggerInterface');

		Vendor('Httpful.Httpful');
		Vendor('Httpful.Request');
		Vendor('Httpful.Http');
		Vendor('Httpful.Bootstrap');

		Vendor('Monolog.Formatter.FormatterInterface');
		Vendor('Monolog.Formatter.NormalizerFormatter');
		Vendor('Monolog.Formatter.LineFormatter');
		Vendor('Monolog.Registry');
		Vendor('Monolog.ErrorHandler');
		Vendor('Monolog.Logger');
		Vendor('Monolog.Handler.HandlerInterface');
		Vendor('Monolog.Handler.AbstractHandler');
		Vendor('Monolog.Handler.AbstractProcessingHandler');
		Vendor('Monolog.Handler.StreamHandler');

		Vendor('JPush.JPushClient');
		Vendor('JPush.Model.Audience');
		Vendor('JPush.Model.PushPayload');
		Vendor('JPush.Model.Options');
		Vendor('JPush.Model.Platform');
		Vendor('JPush.Model.Notification');
		Vendor('JPush.Model.Message');
		Vendor('JPush.JPushLog');
		Vendor('JPush.JPushClient');

		$client = new \JPush\JPushClient('7047c8d0660faa20afffb520', 'baf6cfbed085c5fdce44c875');
		try {
		    $result = $client->push()
		        ->setPlatform(JM\platform('android'))
		        ->setAudience(JM\all)
		        ->setNotification(JM\notification(JM\android('Hello World!', 'EEEEE', 1, array("type"=>"1", "url"=>"http://www.baidu.com"))))
		        //->setMessage(JM\message('Message Content', 'Message Title', 'Message Type', array("key1"=>"value1", "key2"=>"value2")))
		        ->send();
		    echo 'Push Success.' . $br;
		    echo 'sendno : ' . $result->sendno . $br;
		    echo 'msg_id : ' .$result->msg_id . $br;
		    echo 'Response JSON : ' . $result->json . $br;
		} catch (APIRequestException $e) {
		    echo 'Push Fail.' . $br;
		    echo 'Http Code : ' . $e->httpCode . $br;
		    echo 'code : ' . $e->code . $br;
		    echo 'message : ' . $e->message . $br;
		    echo 'Response JSON : ' . $e->json . $br;
		    echo 'rateLimitLimit : ' . $e->rateLimitLimit . $br;
		    echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . $br;
		    echo 'rateLimitReset : ' . $e->rateLimitReset . $br;
		} catch (APIConnectionException $e) {
		    echo 'Push Fail.' . $br;
		    echo 'message' . $e->getMessage() . $br;
		}
	}
}