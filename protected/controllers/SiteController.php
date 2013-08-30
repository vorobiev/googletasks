<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
	
	public function actionGoogle()
	{
		$jgoogleapi = Yii::app()->JGoogleAPI;

		try {
			
	        if(!isset(Yii::app()->session['auth_token'])) {
	            
				$client = $jgoogleapi->getClient();
	            $client->authenticate();
				$token = $client->getAccessToken();
	            Yii::app()->session['auth_token'] = $token;
	        
	        	
	            	
	        } 
			
			
			if(isset($_GET['code']) || isset(Yii::app()->session['auth_token'])) 
			{
		
				$token = Yii::app()->session['auth_token'];
		
				$client = $jgoogleapi->getClient();
	            $client->setAccessToken(Yii::app()->session['auth_token']);
	            
				$userinfo = $jgoogleapi->getService('Oauth2');
		        $info = $userinfo -> userinfo -> get();


				$record=User::model()->find(array(
				      'select'=>'google_id',
				      'condition'=>'google_id=:id',
				      'params'=>array(':id'=>$info -> id))
				);

				if($record===null) 
				{
					$user = new User;
					$user -> name = $info -> name;
					$user -> google_id = $info -> id;
					$user -> token = $token;
					$user -> save();
				} 
				else 
				{
				   	$record -> token = $token;
					$record -> save();
				}
	
	
				$service = $jgoogleapi->getService('Tasks');
	            $userinfo = $jgoogleapi->getService('Oauth2');
		        
				$info = $userinfo -> userinfo -> get();
		 		
				$taskLists =  $service->tasklists->listTasklists();
				
				
				$_info = array('name' => $info -> name , 'id' => $info -> id);
				$_tasklists = array();
				
				
				if($taskLists->getItems())
				{
					foreach ($taskLists->getItems() as $taskList) {
					  $tasks = $service->tasks->listTasks($taskList -> getID());
					  $_tasks = array();
					  if ( $tasks->getItems() )
						  {
						  foreach($tasks->getItems() as $task) {
						  	$_tasks[]= $task->getTitle();
						  }
					  }
					  $_tasklists[] = array(
						'name' => $taskList->getTitle() ,
						'id' => $taskList->getID(),
						'tasks' => $_tasks
					  );
					}
				}
				
	            Yii::app()->session['auth_token'] = $client->getAccessToken();
				$this->render('google',array('tasklists'=>$_tasklists, 'info' => $_info));
	        }
	    }
		catch(Exception $exc) 
		{
			$this -> redirect(array('site/google'));
	        Yii::app()->session['auth_token']=null;
	        throw $exc;
	    }
	}
	
	public function actionRevoke()
	{
		Yii::app()->session['auth_token']=null;
		$this->redirect(array('site/index'));
	}
	

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}