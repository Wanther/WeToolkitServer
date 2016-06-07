<?php
return array(
	'LOG_LEVEL'	=>	'EMERG,ALERT,CRIT,ERR,WARN,DEBUG,SQL',
	'URL_CASE_INSENSITIVE'	=>	true,
	//'SHOW_PAGE_TRACE'	=> true,
	'DB_PARAMS'	=>	array(
		PDO::ATTR_PERSISTENT	=>	true
	),
	'DB_HOST'  =>  'localhost',
    'DB_NAME'   =>  'welearn_toolkit',
	'DB_USER'	=>	'root',
	'DB_PWD'	=>	''
);