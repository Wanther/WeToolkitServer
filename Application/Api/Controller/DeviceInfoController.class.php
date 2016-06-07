<?php

namespace Api\Controller;

use \Think\Controller;

class DeviceInfoController extends Controller{

	public function upload(){

		$appDeviceId = I('app_device_id');
		
		$DeviceInfo = D('DeviceInfo');

		$data = $DeviceInfo->create();

		if(!$data){
			$this->ajaxReturn($DeviceInfo->getError());
		}

		if(!empty($data['mac_address'])){
			$data['mac_address'] = strtoupper($data['mac_address']);
			$data['mac_address'] = preg_replace('/[^a-zA-Z0-9]/', '-', $data['mac_address']);
		}

		if($data['owner'] <= 0){
			unset($data['owner']);
		}

		$DeviceInfo->where("id={$appDeviceId}")->save($data);

		$this->ajaxReturn((object)array());
	}

	public function owner_info($app_device_id){

		$DeviceInfo = D('DeviceInfo');
		$data = $DeviceInfo->find($app_device_id);

		$result = array();

		if(!empty($data)){
			$result['owner'] = $data['owner'];
			$result['user'] = $data['user'];
		}

		$this->ajaxReturn((object)$result);
	}

	public function get_app_device_id(){

		$device_id = I('post.device_id');
		$android_id = I('post.android_id');
		$serial_num = I('post.serial_num');
		$mac_address = I('post.mac_address');

		$queryCondition = array('_logic' => 'or');

		if(!empty($device_id)){
			$queryCondition['device_id'] = $device_id;
		}

		if(!empty($android_id)){
			$queryCondition['android_id'] = $android_id;
		}

		if(!empty($serial_num)){
			$queryCondition['serial_num'] = $serial_num;
		}

		if(!empty($mac_address)){
			$mac_address = strtoupper($mac_address);
			$mac_address = preg_replace('/[^a-zA-Z0-9]/', '-', $mac_address);
			$queryCondition['mac_address'] = $mac_address;
		}

		$DeviceInfo = D('DeviceInfo');
		$dataList = $DeviceInfo->where($queryCondition)->select();

		if (!empty($dataList)) {
			$compare = array('device_id', 'android_id', 'serial_num', 'mac_address');
			foreach ($dataList as $data) {
				$isData = true;
				foreach ($compare as $compareKey) {
					if (!empty($queryCondition[$compareKey]) && !empty($data[$compareKey])
						&& $queryCondition[$compareKey] != $data[$compareKey]) {
						$isData = false;
						break;
					}
				}
				if($isData) {
					$this->ajaxReturn((object)array('app_device_id'=>$data['id']));
				}
			}
		}

		$this->ajaxReturn((object)array('app_device_id'=>$this->generate_app_device_id()));
	}

	protected function generate_app_device_id() {
		$DeviceInfo = D('DeviceInfo');

		$data = $DeviceInfo->create();

		if (!empty($data['mac_address'])) {
			$data['mac_address'] = strtoupper($data['mac_address']);
			$data['mac_address'] = preg_replace('/[^a-zA-Z0-9]/', '-', $data['mac_address']);
		}

		if(!$data){
			return $DeviceInfo->getError();
		}

		return $DeviceInfo->add($data);
	}
}