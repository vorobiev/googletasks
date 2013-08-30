<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Google Tasks',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'JGoogleAPI' => array(
		    'class' => 'ext.JGoogleAPI.JGoogleAPI',
	        //Default authentication type to be used by the extension
	        'defaultAuthenticationType'=>'webappAPI',

	       	'webappAPI' => array(
	            'clientId' => '1033013802765.apps.googleusercontent.com',
	            'clientEmail' => '1033013802765@developer.gserviceaccount.com',
	            'clientSecret' => 'o14-Ro5Dvexlw-aT46VK5_V4',
	            'redirectUri' => 'http://antonvorobiev.ru/tasks/site/google',
	            'javascriptOrigins' => 'http://antonvorobiev.ru/tasks/',
	        ),
	
	        'simpleApiKey' => 'AIzaSyBK7o7rvpehAO_2twx2LP39fsIGLX276N4',

	        //Scopes needed to access the API data defined by authentication type
	        'scopes' => array(
	            'serviceAPI' => array(
	                'drive' => array(
	                    'https://www.googleapis.com/auth/drive.file',
	                ),
	            ),
	            'webappAPI' => array(
	                'tasks' => array(
	                    'https://www.googleapis.com/auth/tasks',
	                ),
					'oauth2' => array(
						'https://www.googleapis.com/auth/userinfo.profile',	
					)
	            ),
	        ),
	        //Use objects when retriving data from api if true or an array if false
	        'useObjects'=>true,
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);