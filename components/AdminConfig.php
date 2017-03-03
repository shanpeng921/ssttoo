<?php


return [
    'adminEmail' => 'admin@example.com',
    'siteUrl' => 'http://local.ssttoo',
	'baseDir' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'smtp' => array (
		'CharSet' => 'UTF-8',
		'SMTPAuth' => true,
		'SMTPSecure' => 'tls',
		'Host' => 'smtp.office365.com',
		'Port' => '587',
		'Username' => 'leave_admin@test.com',
		'Password' => 'tlt1234$',
	),
	//正式环境设置为 0 
	'sysDebug' => 0,
];
