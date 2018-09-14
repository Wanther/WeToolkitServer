<?php

namespace Wxpub\Controller;

use \Think\Controller;

class IndexController extends Controller {

    public function index() {

        $token = cookie('PHPSESSID');

        echo 'token='.$token;

        exit;

        $this->display('index');
    }
}