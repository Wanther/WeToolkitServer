<?php
function is_login(){
	$user = get_login_user();
	if($user && isset($user['id'])){
		return $user['id'];
	}
}

function get_login_user(){
	if(!Admin\Model\AdminUserModel::$loginUser){
		$uid = session('uid');
		if($uid){
			$AdminUser = new Admin\Model\AdminUserModel();
			$user = $AdminUser->field('password', true)->find($uid);

			//设置权限资源
			$userMenuList = array();
			$menuList = C('SYS_MENU');
			if(!empty($user['auth']) && !empty($menuList)){
				foreach ($menuList as $menu) {
					$authViewList = array();
					if(isset($menu['children']) && !empty($menu['children'])){
						foreach ($menu['children'] as $m) {
							if(has_auth($user, $m['id'])){
								$authViewList[] = $m;
							}
						}
					}
					if(!empty($authViewList)){
						$menu['children'] = $authViewList;
						$userMenuList[] = $menu;
					}
				}
			}

			$user['menuList'] = $userMenuList;

			Admin\Model\AdminUserModel::$loginUser = $user;
		}
	}
	
	return Admin\Model\AdminUserModel::$loginUser;
}

function has_auth($user, $authId){
	if(!isset($user['auth']) || empty($user['auth'])){
		return false;
	}

	return in_array($authId, explode(',', $user['auth']));
}

function get_sort_info(){
	$sortInfo = I('get.sort');
	if(!empty($sortInfo)){
		$sortInfo = explode('-', $sortInfo);
		if(isset($sortInfo[0]) && isset($sortInfo[1])){
			return array($sortInfo[0]=>$sortInfo[1]);
		}
	}
}

function get_return_url($defaultUrl = ''){
	$url = cookie('return_url');
	if(empty($url)){
		$url = $defaultUrl;
	}else{
		cookie('return_url', null);
	}

	return $url;
}

function serialize_upload($name){
	if(isset($_POST[$name])){
		$files = json_decode($_POST[$name]);
		$fileCount = count($files);
		if($fileCount > 0){
			$filesStr = '';
			for($i = 0; $i < $fileCount; $i++){
				if($i > 0){
					$filesStr .= ',';
				}
				$filesStr .= $files[$i]->id;
			}
			$_POST[$name] = $filesStr;
		}
	}
}

function desialize_pic($filesStr){
	$picList = array();
	if($filesStr){
		$picIds = explode(',', $filesStr);
		$picList = M('Picture')
			->field('id,path')
			->where(array('id'=>array('IN', $picIds)))
			->select();

		if($picList){
			foreach ($picList as $key => &$value) {
				$value['path'] = C('RES_ROOT').$value['path'];
			}
		}
	}

	return json_encode($picList);
}

function get_upload_app_file_name($app) {
	return "{$app['code']}_{$app['platform']}";
}

function list_to_tree($list, $pk='id', $pid='pid', $child='_child', $root=0) {
    $tree = array();// 创建Tree
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[$data[$pk]] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}