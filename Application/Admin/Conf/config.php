<?php
defined('__RES__') or define('__RES__', __ROOT__);

return array(
	'URL_HTML_SUFFIX'	=>	'',

	'TMPL_PARSE_STRING' => array(
		'__STATIC__' => __ROOT__ . '/Public/static',
		'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
		'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
		'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js'
	),

	/* 图片上传相关配置 */
	'PICTURE_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'maxSize'  => 2 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
		'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Public/Uploads/Picture/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	),

	'SYS_MENU'	=>	array(
		array(
			'id'	=>	10001,
			'name'	=>	'系统',
			'icon'	=>	'icon-cog',
			'children'	=>	array(
				array(
					'id'	=>	101,
					'name'	=>	'权限管理',
					'icon'	=>	'icon-sitemap',
					'action'	=>	'User/lists'
				),
				array(
					'id'	=>	104,
					'name'	=>	'字典管理',
					'icon'	=>	'icon-book',
					'action'	=>	'Lookup/lists'
				)
			)
		),
		array(
			'id'	=>	10002,
			'name'	=>	'开发',
			'icon'	=>	'icon-wrench',
			'children'	=>	array(
				array(
					'id'	=>	201,
					'name'	=>	'APP管理',
					'icon'	=>	'icon-android',
					'action'	=>	'AppRelease/lists'
				),
				array(
					'id'	=>	202,
					'name'	=>	'API模拟',
					'icon'	=>	'icon-bug',
					'action'	=>	'ApiEmulator/lists'
				),
				array(
					'id'	=>	203,
					'name'	=>	'渠道包制作',
					'icon'	=>	'icon-truck',
					'action'	=>	'ChannelApk/make_channel_apk'
				)
			)
		),
		array(
			'id'	=>	10003,
			'name'	=>	'测试',
			'icon'	=>	'icon-bug',
			'children'	=>	array(
				array(
					'id'	=>	301,
					'name'	=>	'测试机管理',
					'icon'	=>	'icon-mobile-phone',
					'action'	=>	'DeviceInfo/lists'
				)
			)
		),
		array(
			'id'	=>	10004,
			'name'	=>	'内容',
			'icon'	=>	'icon-bug',
			'children'	=>	array(
				array(
					'id'	=>	401,
					'name'	=>	'单词',
					'icon'	=>	'icon-mobile-phone',
					'action'	=>	'Word/lists'
				)
			)
		)
	)
);