<?php
namespace Admin\Controller;

class AchievementController extends AdminController{

	public function lists(){
		$this->authView(402);

		$name = I('get.name');

		$condition = array();
		if($name){
			$condition['name'] = array('LIKE', "%{$name}%");
		}

		$Achievement = D('Achievement');

		$totalCount = $Achievement->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$Achievement->field(true)->where($condition);
		$sortInfo = get_sort_info();
		if(!empty($sortInfo)){
			$Achievement->order($sortInfo);
		}
		$dataList = $Achievement->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $dataList);
		$this->assign('page', $Page->show());

		$this->display();
	}

	public function add(){
		$this->authView(402);

		if(IS_POST){
			$Achievement = D('Achievement');
			$data = $Achievement->create();

			if(!$data){
				$this->errorInput($Achievement->getError(), 'Achievement/edit');
			}

			$id = $Achievement->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'Achievement/lists');
			}

			$this->successMessage('添加成功', 'Achievement/lists');
		}else{
			$this->display('edit');
		}
	}

	public function edit($id) {
		$this->authView(402);

		$Achievement = D('Achievement');

		if (IS_POST) {
			$data = $Achievement->create();
			if (!$data) {
				$this->errorInput($Achievement->getError(), 'Achievement/edit');
			}

			$Achievement->save();

			$this->successMessage('操作成功', get_return_url('Achievement/lists'));
		} else {
			$data = $Achievement->field(true)->find($id);
			$this->assign('data', $data);
			$this->display('edit');
		}
	}

	public function delete(){
		$this->authView(402);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('Achievement/lists')));
		}

		$Achievement = D('Achievement');
		$AchievementItem = M('AchievementItem');

		if(is_array($id)){
			$AchievementItem->where(array('pid'=>array('IN', $id)))->delete();
			$Achievement->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$AchievementItem->where(array('pid'=>(int)$id))->delete();
			$Achievement->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('Achievement/lists')));
	}

	public function itemlist($id) {
		$this->authView(402);

		$parent = M('Achievement')->find($id);

		$condition = array('pid'=>$id);

		$AchievementItem = M('AchievementItem');

		$totalCount = $AchievementItem->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$dataList = $AchievementItem->limit($Page->firstRow, $Page->listRows)->select();

		$this->assign('parent', $parent);
		$this->assign('dataList', $dataList);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function itemimport() {

		$this->authView(402);

		$pid = I('post.pid');

		$upload = new \Think\Upload(array(
			'mimes'    => '', //允许上传的文件MiMe类型
			'maxSize'  => 2 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
			'exts'     => 'xls,xlsx', //允许上传的文件后缀
			'autoSub'  => true, //自动子目录保存文件
			'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
			'rootPath' => './Public/Uploads/Doc/', //保存根路径
			'savePath' => '', //保存路径
			'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			'saveExt'  => '', //文件保存后缀，空则使用原后缀
			'replace'  => false, //存在同名是否覆盖
			'hash'     => true, //是否生成hash编码
			'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
		));

		$uploadInfo = $upload->upload();
		if (!$uploadInfo) {
			$this->errorMessage($upload->getError(), get_return_url(U('Achievement/itemlist', array('id'=>$pid))));
		}

		$fileInfo = $uploadInfo['achievement_detail'];

		$excelFile = $upload->rootPath.$fileInfo['savepath'].$fileInfo['savename'];

		Vendor('PHPExcel.PHPExcel');

		$excel = \PHPExcel_IOFactory::load($excelFile, $encode = 'utf-8');

		$sheet = $excel->getSheet(0);
		$maxCol = \PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
		$maxRow = $sheet->getHighestRow();

		$headers = array();
		$dataList = array();

		// excel headers
		for ($col = 0; $col < $maxCol; $col++) {
			$cellValue = $sheet->getCellByColumnAndRow($col, 1)->getValue();
			
			array_push($headers, $cellValue);
		}

		for ($row = 2; $row <= $maxRow ; $row++) {
			$data = array('pid'=>$pid, 'detail'=>array());
			for ($col = 0; $col < $maxCol; $col++) {
				$cellValue = $sheet->getCellByColumnAndRow($col, $row)->getValue();

				$key = $headers[$col];
				if ($key == '姓名') {
					$data['name'] = $cellValue;
				} elseif ($key == '手机号' || $key == '手机号码') {
					$data['phone'] = $cellValue;
				} elseif ($key == '邮箱') {
					$data['email'] = $cellValue;
				} else {
					array_push($data['detail'], array('name'=>$key, 'value'=>$cellValue));
				}
			}

			$data['detail'] = json_encode($data['detail']);
			array_push($dataList, $data);
		}

		$AchievementItem = M('AchievementItem');
		$AchievementItem->where(array('pid'=>$pid))->delete();
		if (!empty($dataList)) {
			$AchievementItem->addAll($dataList);
		}

		$this->successMessage('导入成功', get_return_url(U('Achievement/itemlist', array('id'=>$pid))));

	}

}