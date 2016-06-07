<?php
namespace Admin\Controller;

use Common\Model\PictureModel;
use Think\Upload;

class FileController extends AdminController {

	public function upload_picture(){
		//TODO: 用户登录检测

		/* 返回标准数据 */
		$return  = array('status' => 0, 'info' => '上传成功', 'data' => '');

		/* 调用文件上传组件上传文件 */
		$Picture = D('Common/Picture');
		$info = $Picture->upload(
			$_FILES,
			C('PICTURE_UPLOAD')
		);

		/* 记录图片信息 */
		if($info){
			$return['status'] = 0;
			$return['path'] = C('RES_ROOT').$info['fileUpload']['path'];
			$return = array_merge($info['fileUpload'], $return);
		} else {
			$return['status'] = 99;
			$return['info']   = $Picture->getError();
		}

		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}

}