<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
define('ROLE_ADMIN', '2');
define('ROLE_USER', '1');
 
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',        
    
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
                'admin',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Rdbhnegdj95',			
		),
		
	),     
        
	// application components
	'components'=>array(

		'user'=>array(
                        'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
            
                'authManager' => array(
                // Будем использовать свой менеджер авторизации
                'class' => 'PhpAuthManager',
                // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
                'defaultRoles' => array('guest'),
                ),
            
                // uncomment the following to enable URLs in path-format		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                                'home'=>'site/data',                                
                                'add'=>'admin/locations/add',                               
			),
                        'showScriptName' => false,
		),
		

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				
			),
		),

	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
                'googleKey'=>require(dirname(__FILE__).'/mapkey.php'),
	),
);
