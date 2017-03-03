<?php


return [
    'adminEmail' => 'admin@example.com',
    'testEmail' => 'peng.shan@happyelements.com',
    'siteUrl' => 'http://local.leaveyii2',
	'baseDir' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'smtp' => array (
		'CharSet' => 'UTF-8',
		'SMTPAuth' => true,
		'SMTPSecure' => 'tls',
		'Host' => 'smtp.office365.com',
		'Port' => '587',
		'Username' => 'leave_admin@happyelements.com',
		'Password' => 'tlt1234$',
	),
	//正式环境设置为 0 
	'sysDebug' => 0,
];
