<?php
namespace Admin\Controller;

class NewStudentController extends AdminController{

	public function lists(){
		$this->authView(403);

		$name = I('get.name');

		$condition = array();
		if($name){
			$condition['name'] = array('LIKE', "%{$name}%");
		}

		$NewStudent = D('NewStudent');

		$totalCount = $NewStudent->where($condition)->count(1);
		$Page = new \Org\Util\Page($totalCount);
		$Page->setConfig('theme', '%UP_PAGE% %FIRST%  %LINK_PAGE% %END% %DOWN_PAGE%');

		$NewStudent->field(true)->where($condition);
		$sortInfo = get_sort_info();
		if(!empty($sortInfo)){
			$NewStudent->order($sortInfo);
		}
		$dataList = $NewStudent->limit($Page->firstRow, $Page->listRows)->select();

		cookie('return_url', $_SERVER['REQUEST_URI']);

		$this->assign('dataList', $dataList);
		$this->assign('page', $Page->show());

		$this->display("lists_{$this->getViewName()}");
	}

	public function add(){
		$this->authView(403);

		if(IS_POST){
			$NewStudent = D('NewStudent');
			$data = $NewStudent->create();

			if(!$data){
				$this->errorInput($NewStudent->getError(), "NewStudent/edit_{$this->getViewName()}");
			}

			$id = $NewStudent->data($data)->add();

			if(!$id){
				$this->errorMessage('添加失败', 'NewStudent/lists');
			}

			$this->successMessage('添加成功', 'NewStudent/lists');
		}else{
			$this->display("edit_{$this->getViewName()}");
		}
	}

	public function edit($id) {
		$this->authView(403);

		$NewStudent = D('NewStudent');

		if (IS_POST) {

			$origin = $NewStudent->find($id);

			$data = $NewStudent->create();
			if (!$data) {
				$this->errorInput($NewStudent->getError(), "NewStudent/edit_{$this->getViewName()}", array(), array(), $origin);
			}

			$NewStudent->save();

			$this->successMessage('操作成功', get_return_url("NewStudent/lists"));
		} else {
			$data = $NewStudent->field(true)->find($id);
			$this->assign('data', $data);

			$this->display("edit_{$this->getViewName()}");
		}
	}

	/*public function update($id) {
		$this->authView(403);

		if (!IS_POST) {
			$this->errorMessage('更新失败', 'NewStudent/lists');
		}

		$NewStudent = D('NewStudent');

		$data = I('post.');

		unset($data['id']);

		$NewStudent->where(array('id'=>$id))->save($data);

		$this->successMessage('操作成功', get_return_url('NewStudent/lists'));

	}*/

	public function delete(){
		$this->authView(403);
		$id = I('id');
		if(empty($id)){
			$this->errorMessage('请选择要删除的记录', get_return_url(U('NewStudent/lists')));
		}

		$NewStudent = D('NewStudent');

		if(is_array($id)){
			$NewStudent->where(array('id'=>array('IN', $id)))->delete();
		}else{
			$NewStudent->where(array('id'=>(int)$id))->delete();
		}

		$this->successMessage('删除成功', get_return_url(U('NewStudent/lists')));
	}

	public function exportExcel() {
		$this->authView(403);

		$dataList = D('NewStudent')->field('created,created_by,last_upd,last_upd_by', true)->select();

		if (empty($dataList)) {
			$this->error('没有可导出的数据');
		}

		Vendor('PHPExcel.PHPExcel');

		$excel = new \PHPExcel();
		$excel->getProperties()->setCreator($this->getViewName())
			->setLastModifiedBy($this->getViewName())
			->setTitle("新生报到");

		$sheet = $excel->setActiveSheetIndex(0);
		$sheet->setTitle('新生');

		$headerMapping = array(
			'id' => 'ID',
			'exam_num' => '考号',
			'name' => '姓名',
			'id_card_no' => '身份证号',
			'gender' => '性别',
			'nation' => '民族',
			'major' => '专业',
			'is_payed' => '交费',
			'is_stay' => '住宿',
			'is_pad' => 'PAD',
			'height' => '身高',
			'weight' => '体重',
			'shoe_size' => '鞋码',
			'shape' => '体型',
			'middle_school' => '初中',
			'middle_school_class' => '初中班级',
			'father_name' => '父亲姓名',
			'father_phone' => '父亲电话',
			'mother_name' => '母亲姓名',
			'mother_phone' => '母亲电话',
			'address' => '地址',
			'desc_txt_01' => '备注（教务处）',
			'desc_txt_02' => '备注（总务处）',
			'desc_txt_03' => '备注（政教处）',
			'desc_txt_04' => '备注（生活处）',
			'desc_txt_05' => '备注（电教处）'
		);

		$column = 0;
		foreach ($headerMapping as $key => $value) {
			$sheet->setCellValueExplicitByColumnAndRow($column, 1, $value);
			$column++;
		}

		$row = 2;
		foreach ($dataList as $value) {
			$column = 0;
			foreach ($headerMapping as $k => $v) {
				$cellValue = $value[$k];
				switch ($k) {
					case 'gender':
						$cellValue = lookup_value('GENDER', $cellValue);
						break;

					case 'nation':
						$cellValue = lookup_value('NATION', $cellValue);
						break;

					case 'major':
						$cellValue = lookup_value('MAJOR', $cellValue);
						break;

					case 'middle_school_class':
						$cellValue = lookup_value('MIDDLE_SCHOOL_CLASS', $cellValue);
						break;

					case 'shape':
						$cellValue = lookup_value('BODY_SHAPE', $cellValue);
						break;
				}
				$sheet->setCellValueExplicitByColumnAndRow($column, $row, $cellValue);
				$column++;
			}
			$row++;
		}
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		header('Cache-Control: max-age=0');

		\PHPExcel_IOFactory::createWriter($excel, 'Excel5')->save('php://output');

		exit;
	}

	protected function getViewName() {
		$view = \Admin\Model\AdminUserModel::$loginUser['username'];
		if ($view == 'wangzaiyou') {
			$view = 'dianjiaochu';
		}

		return $view;
	}

}