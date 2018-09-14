<?php

namespace Wxpub\Controller;

use \Think\Controller;

class ConfigController extends Controller {
    public function verify() {
        $signature = I('signature');

        $timestamp = I('timestamp');

        $nonce = I('nonce');

        $echostr = I('echostr');

        $token = 'wanghetest';

        $params = array($token, $timestamp, $nonce);

        $params = sort($params);

        $check = sha1(join('', $params));

        if ($check == $signature) {
            die($echostr);
        } else {
            die('');
        }
    }
}