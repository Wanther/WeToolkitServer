<?php
namespace Home\Controller;

use Think\Controller;

class AppController extends Controller {
    public function detail($id){
    	$this->curNav = 1;

    	$App = M('AppRelease');
    	$app = $App->find($id);

    	if (empty($app)) {
    		$this->error('404');
    	}

    	$this->assign('app', $app);

        $this->display();
    }

    public function log_list($appId, $start, $limit=20) {
        $AppLog = M('ReleaseLog');
        $logList = $AppLog->where(array('pid'=>$appId))->order('created desc')->limit($start, $limit)->select();

        foreach ($logList as $key => $value) {
            $logList[$key]['created'] = date('Y-m-d H:i:s', $value['created']);
            $logList[$key]['server_env_txt'] = lookup_value('SERVER_ENV', $value['server_env']);
        }

        $this->ajaxReturn(array('data'=>$logList));
    }
}