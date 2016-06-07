<?php
namespace Admin\Controller;

class AppReleaseController extends AdminController{
	public function lists(){
		$this->authView(201);

		$AppRelease = D('AppRelease');

		$appList = $AppRelease->order('seq_num')->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $appList);

		$this->display();
	}

	public function change_status($status) {

		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('AppRelease/lists')));
		}

		$status = $status == 'Y' ? 'Y' : 'N';

		$AppRelease = D('AppRelease');

		if(is_array($id)){
			$AppRelease->where(array('id'=>array('IN', $id)))->setField('inactive', $status);
		}else{
			$AppRelease->where(array('id'=>(int)$id))->setField('inactive', $status);
		}

		$this->successMessage('操作成功', get_return_url(U('AppRelease/lists')));
	}

	public function add(){
		$this->authView(201);

		if(!IS_POST){
			return $this->display('edit');
		}

		$AppRelease = D('AppRelease');
		$data = $AppRelease->create();

		if(!$data){
			$this->errorInput($AppRelease->getError(), 'AppRelease/edit');
		}

		$id = $AppRelease->data($data)->add();

		if(!$id){
			$this->errorMessage('添加失败', 'AppRelease/lists');
		}

		$this->successMessage('添加成功', 'AppRelease/lists');
	}

	public function edit($id) {
		$this->authView(201);

		if (!IS_POST) {
			$AppRelease = D('AppRelease');
			
			$data = $AppRelease->find($id);

			if (empty($data)) {
				$this->errorMessage('该记录不存在或已删除', 'AppRelease/lists');
			}

			$this->assign('data', $data);

			return $this->display('edit');
		}

		$AppRelease = D('AppRelease');
		$data = $AppRelease->create();

		if(!$data){
			$this->errorInput($AppRelease->getError(), 'AppRelease/edit');
		}

		$AppRelease->save();

		$this->successMessage('修改成功', 'AppRelease/lists');
	}

	public function release($id) {
		$this->authView(201);

		$AppRelease = D('AppRelease');

		$app = $AppRelease->find($id);

		if (empty($app)) {
			$this->errorMessage('该记录不存在或已删除', 'AppRelease/lists');
		}

		$this->assign('app', $app);

		if (IS_POST) {
			$AppReleaseLog = D('AppReleaseLog');
			$data = $AppReleaseLog->create();

			if (!$data) {
				$this->errorInput($AppReleaseLog->getError(), 'AppRelease/edit_log');
			}

			$setting = array(
				'mimes'    => '', //允许上传的文件MiMe类型
				'maxSize'  => 20 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
				'exts'     => 'apk,ipa', //允许上传的文件后缀
				'autoSub'  => false, //自动子目录保存文件
				'rootPath' => '.', //保存根路径
				'savePath' => '/Public/Uploads/App/', //保存路径
				'saveName' => array('get_upload_app_file_name', array($app)), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
				'saveExt'  => '', //文件保存后缀，空则使用原后缀
				'replace'  => true, //存在同名是否覆盖
				'hash'     => false, //是否生成hash编码
				'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
			);

			$Upload = new \Think\Upload($setting);
			$info = $Upload->upload($_FILES);

			if (!$info) {
				$this->errorInput(array('fileUpload'=>$Upload->getError()), 'AppRelease/edit_log');
			} else {
				// 包地址
				$pkgPath = $info['fileUpload']['savepath'].$info['fileUpload']['savename'];

				// 如果没有生成二维码，生成
				if (empty($app['qrcode_url']) || empty($app['pkg_path'])) {
					$qrcodePath = './Public/Uploads/Picture/'.$app['code'].'.png';

					$pkgUrl = 'http://'.$_SERVER["HTTP_HOST"].__ROOT__.$pkgPath;
					vendor('phpqrcode.qrlib');
					\QRcode::png($pkgUrl, $qrcodePath, 'L', 5);
					
					$updatedApp = array('pkg_path'=>$pkgPath, 'qrcode_url'=>substr($qrcodePath, 1));

					$AppRelease->where("id={$app['id']}")->save($updatedApp);
				}

				// 读取apk文件版本号、版本名称、appMode
				vendor('ApkParser.ApkParser');
				$apkParser = new \ApkParser();
				$apkParser->open('.'.$pkgPath);
				$data['ver_code'] = $apkParser->getVersionCode();
				$data['ver_name'] = $apkParser->getVersionName();
				$appMode = $apkParser->getAttribute('manifest/application/meta-data[0]', 'android:value');

				$appMode = explode(',', $appMode);

				$data['server_env'] = trim($appMode[count($appMode) - 1]);

				$AppReleaseLog->data($data)->add();

				$pushBearyChat = I('post.push_bearychat');

				if ($pushBearyChat == 'Y') {
					$this->notifyTeam($app, $data);
				}

				$pushWelearnToolkit = I('post.push_welearntoolkit');

				if ($pushWelearnToolkit == 'Y') {
					$this->notifyWelearnToolkit($app, $data);
				}

				$this->successMessage('发布成功', 'AppRelease/lists');
			}

		} else {
			$this->display('edit_log');
		}
	}

	public function qrcode_reset($id) {
		$this->authView(201);

		$AppRelease = D('AppRelease');

		$app = $AppRelease->find($id);

		if (empty($app)) {
			$this->errorMessage('该记录不存在或已删除', 'AppRelease/lists');
		}

		if (empty($app['pkg_path'])) {
			$this->errorMessage('安装包不存在，请直接更新发布', 'AppRelease/lists');
		}

		$pkgUrl = 'http://'.$_SERVER["HTTP_HOST"].__ROOT__.$app['pkg_path'];
		$qrcodePath = './Public/Uploads/Picture/'.$app['code'].'.png';

		vendor('phpqrcode.qrlib');
		\QRcode::png($pkgUrl, $qrcodePath, 'L', 5);

		$this->successMessage('二维码重置成功', get_return_url(U('AppRelease/lists')));
	}

	protected function notifyTeam($app, $log) {
		$message = '**'.$app['name'].'**发布了新的测试版本：';
		$message .= '**'.$log['ver_name'].'-'.lookup_value('SERVER_ENV', $log['server_env']).'环境**';
		$message .= ' [点击查看详情]('.U('Home/App/detail', array('id'=>$app['id']), true, true).')';
		//send to beary chat
		$robotData = array(
			'text' => $message,
			'markdown' => true,
			'channel' => '所有人',
			'attachments' => array(
				array(
					'text' => $log['msg'],
					'color' => '#00bdef'
				)
			)
		);

		$bearychatRobot = 'http://hook.bearychat.com/=bw55t/incoming/ed5cb158e1bca58edbd4ca12d1978127';
		$options = array(
			'http'	=>	array(
				'method' => 'POST',
				'header' => 'Content-type: application/json',
				'content' => json_encode($robotData),
				'timeout' => 30
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($bearychatRobot, false, $context);
	}

	protected function notifyWelearnToolkit($app, $log) {
		$msgId = 1000 + $app['id'];

		Vendor('JPush.JPush');

		$alert = $app['name'].$log['ver_name'].'-'.lookup_value('SERVER_ENV', $log['server_env']).'环境发布了新的测试版本';
		$openUrl = U('Home/App/detail', array('id'=>$app['id']), true, true);

		$client = new \JPush(C('JPUSH_APPKEY_WLT'), C('JPUSH_SECRET_WLT'), null);
		$result = $client->push()
				->setPlatform(array('android'))
				//->addAlias('dev_id_2')
				->addAllAudience()
				->setMessage($alert, '微懒兔', 'type', array("_msg_id"=>$msgId, "_msg_type"=>"1", "url"=>$openUrl))
				->setOptions(100000, 3600, null, false)
				->send();
	}

}