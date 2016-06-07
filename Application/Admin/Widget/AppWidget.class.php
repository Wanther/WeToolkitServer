<?php
namespace Admin\Widget;

use \Think\Controller;

class AppWidget extends Controller{
	public function showNav($user, $currentView){
		if(empty($user) || !isset($user['menuList']) || empty($user['menuList'])){
			return;
		}

		$html = '<ul class="nav">';

		$html .= '<li';
		if(!$currentView){
			$html .= ' class="active"';
		}
		$html .= '><a href="'.U('Index/index').'">首页</a>';
		$html .= '</li>';

		foreach ($user['menuList'] as $menu) {
			$active = false;

			if(isset($menu['children']) && !empty($menu['children'])){
				foreach($menu['children'] as $view){
					if($currentView == $view['id']){
						$active = true;
						break;
					}
				}
			}

			$html .= "<li class=\"dropdown";
			if($active){
				$html .= ' active';
			}
			$html .= '">';
			$html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">';
			if($menu['icon']){
				$html .= "<span class=\"{$menu['icon']}\"></span> ";
			}
			$html .= "{$menu['name']} <b class=\"caret\"></b></a>";

			$html .= '<ul class="dropdown-menu">';
			foreach ($menu['children'] as $m) {
				$html .= '<li';
				if($m['id'] == $currentView){
					$html .= ' class="active"';
				}
				$html .= '>';

				$html .= "<a href=\"".U($m['action'])."\">";
				if($m['icon']){
					$html .= "<span class=\"{$m['icon']}\"></span> ";
				}
				$html .= "{$m['name']}</a>";

				$html .= '</li>';
			}
			$html .= '</ul>';

			$html .= "</li>";
		}

		$html .= '</ul>';

		return $html;
	}

	public function showNavLeft($user, $currentView){
		if(empty($user) || !isset($user['menuList']) || empty($user['menuList'])){
			return;
		}

		$html = '<ul class="nav nav-list app-sidebar">';

		foreach ($user['menuList'] as $menu) {
			if(isset($menu['children']) && !empty($menu['children'])){
				$current = false;
				foreach ($menu['children'] as $view) {
					if($currentView == $view['id']){
						$current = true;
					}
				}

				if($current){
					foreach ($menu['children'] as $view) {
						$html .= '<li';
						$href = U($view['action']);
						if($currentView == $view['id']){
							$html .= ' class="active"';
							$href = 'javascript:;';
						}
						$html .= '>';
						$html .= "<a href=\"{$href}\"><i class=\"icon-chevron-right pull-right\"></i><span class=\"{$view['icon']}\"></span> {$view['name']}</a>";
						$html .= '</li>';
					}
					break;
				}
			}
		}

		$html .= '</ul>';

		return $html;
	}
}