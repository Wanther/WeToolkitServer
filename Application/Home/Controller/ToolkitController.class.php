<?php

namespace Home\Controller;

use Common\Controller\AppController;

class ToolkitController extends AppController{

	public function index(){
		$this->assign('curNav', 2);
		$this->display('index');
	}

	public function get_apk_info() {
		$setting = array(
			'mimes'    => '', //允许上传的文件MiMe类型
			'maxSize'  => 20 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
			'exts'     => 'apk', //允许上传的文件后缀
			'autoSub'  => false, //自动子目录保存文件
			'rootPath' => '.', //保存根路径
			'savePath' => '/Public/Uploads/Tmp/Apk/', //保存路径
			'saveName' => array('uniqid',''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			'saveExt'  => '', //文件保存后缀，空则使用原后缀
			'replace'  => true, //存在同名是否覆盖
			'hash'     => false, //是否生成hash编码
			'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
		);

		$Upload = new \Think\Upload($setting);
		$info = $Upload->upload($_FILES);

		if (!$info) {
			$this->error($Upload->getError());
		} else {
			$data = array();

			$pkgPath = $info['apk']['savepath'].$info['apk']['savename'];

			vendor('ApkParser.ApkParser');
			$apkParser = new \ApkParser();
			
			$apkParser->open('.'.$pkgPath);

			$data['origin_name'] = $info['apk']['name'];
			$data['package'] = $apkParser->getPackage();
			$data['ver_code'] = $apkParser->getVersionCode();
			$data['ver_name'] = $apkParser->getVersionName();

			// 读取服务器环境
			$appMode = $apkParser->getAttribute('manifest/application/meta-data[0]', 'android:name');
			if ($appMode == 'appMode') {
				$appMode = $apkParser->getAttribute('manifest/application/meta-data[0]', 'android:value');
				$appMode = explode(',', $appMode);

				$data['server_env'] = trim($appMode[count($appMode) - 1]);
				$data['server_env'] = lookup_value('SERVER_ENV', $data['server_env']);
			}

			// 读取渠道标识
			$apkFile = zip_open('.'.$pkgPath);
			if ($apkFile) {
				while($zipEntry = zip_read($apkFile)) {
					$zipEntryName = zip_entry_name($zipEntry);
					if (preg_match("/^META-INF\/channel_(\S+)\.txt$/", $zipEntryName, $matchs)) {
						$data['channel'] = $matchs[1];
						break;
					}
				}
				zip_entry_close($zipEntry);
			}
			zip_close($apkFile);

			unlink('.'.$pkgPath);

			$this->ajaxReturn($data);
		}
	}
}