<?php
namespace Api\Controller;

use \Think\Controller;

class PayController extends Controller {
	public function product_list() {
		C('TMPL_TEMPLATE_SUFFIX', '.json');

		$jsonString = $this->fetch('product_list');

		$this->ajaxReturn(json_decode($jsonString));
	}

	public function purchase_order() {
		$order = array();
		$order['order'] = time();
		$order['order_name'] = '11份四级模拟题套卷';
		$order['total_fee'] = 0.01;
		$order['description'] = '6份四级真题套卷';
		$order['timeout'] = '30m';
		$order['callback_url'] = 'https://cet.uda100.com/payment_callback/test/';

		$this->ajaxReturn($order);
	}
}