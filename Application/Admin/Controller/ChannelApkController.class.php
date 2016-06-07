<?php
namespace Admin\Controller;

class ChannelApkController extends AdminController{
	public function make_channel_apk() {

		$this->authView(203);

		$channelApkDir = '/Public/Uploads/App/channelApk/';

		if (IS_POST) {
			$channel = I('post.channel');
			if (!preg_match("/^[0-9a-zA-Z]+$/", $channel)) {
				$this->errorInput(array('channel'=>'渠道标识只能是数字字母的组合'));
			}

			$setting = array(
				'mimes'    => '', //允许上传的文件MiMe类型
				'maxSize'  => 20 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
				'exts'     => 'apk', //允许上传的文件后缀
				'autoSub'  => false, //自动子目录保存文件
				'rootPath' => '.', //保存根路径
				'savePath' => $channelApkDir, //保存路径
				'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
				'saveExt'  => '', //文件保存后缀，空则使用原后缀
				'replace'  => true, //存在同名是否覆盖
				'hash'     => false, //是否生成hash编码
				'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
			);
			$Upload = new \Think\Upload($setting);
			$info = $Upload->upload($_FILES);

			if (!$info) {
				$this->errorInput(array('sourceApk'=>$Upload->getError()), 'ChannelApk/make_channel_apk');
			} else {
				$pkgPath = '.'.$info['sourceApk']['savepath'].$info['sourceApk']['savename'];

				$apkFile = zip_open($pkgPath);
				if ($apkFile) {
					while($zipEntry = zip_read($apkFile)) {
						$zipEntryName = zip_entry_name($zipEntry);
						if (preg_match("/^META-INF\/channel_(\S+)\.txt$/", $zipEntryName, $matchs)) {
							$this->errorInput(array('sourceApk'=>"渠道标识文件已存在{$matchs[1]}"), 'ChannelApk/make_channel_apk');
						}
					}
					zip_entry_close($zipEntry);
				}
				zip_close($apkFile);

				vendor('ApkParser.ApkParser');
				$apkParser = new \ApkParser();
				$apkParser->open($pkgPath);

				$targetApk = '.'.$info['sourceApk']['savepath'] . $apkParser->getPackage() . '-' . $apkParser->getVersionName();

				$appMode = $apkParser->getAttribute('manifest/application/meta-data[0]', 'android:name');
				if ($appMode == 'appMode') {
					$appMode = $apkParser->getAttribute('manifest/application/meta-data[0]', 'android:value');
					$appMode = explode(',', $appMode);

					$appMode = trim($appMode[count($appMode) - 1]);

					$targetApk .= '-'.$appMode;
				}

				$targetApk .= "-{$channel}.apk";

				if (file_exists($targetApk)) {
					unlink($targetApk);
				}

				rename($pkgPath, $targetApk);

				// 调用python打包脚本
				passthru("python ./build_channel_apk.py {$targetApk} {$channel}");

				$this->successMessage('生成成功，请在生成历史中下载', 'ChannelApk/make_channel_apk');
			}

		}

		$channelApkList = array();
		$fileList = scandir('.'.$channelApkDir, 1);
		foreach ($fileList as $fileName) {
			if (preg_match("/\.apk$/", $fileName)) {
				array_push($channelApkList, array('fileName'=>$fileName, 'time'=>filemtime('.'.$channelApkDir.$fileName)));
			}
		}

		$this->assign('dataList', $channelApkList);

		$this->display();
	}
}