<?php

class UsersController extends CController
{
	public function filters()
    {
        return array( 'accessControl' ); // perform access control for CRUD operations
    }
 
   
    public function accessRules()
    {
        return array(
            array('deny', 'users'=>array('?'),
            ),
        );
    }

	public function actionIndex()
	{
		$users = User::model()->findAll();
		$this -> render('index', array('users'=>$users));
	}
	
	public function actionShow()
	{
		$id = $_GET['id'];
		$user = User::model()->findByPk($id);
		
		$jgoogleapi = Yii::app()->JGoogleAPI;
		$client = $jgoogleapi->getClient();
        $client->setAccessToken($user -> token);

		$service = $jgoogleapi->getService('Tasks');
        
		$taskLists =  $service->tasklists->listTasklists();
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
		
		$this -> render('show', array('user'=>$user, 'tasklists' => $_tasklists));

	}
	
	public function actionAddtask()
	{
		
		
		$user = User::model() -> findByPk($_POST['user_id']);
		if($user === null)
		{
			Yii::app()->request->redirect($_SERVER['HTTP_REFERER']);
		}
		
		
		$jgoogleapi = Yii::app()->JGoogleAPI;
		$client = $jgoogleapi->getClient();
        $client->setAccessToken($user -> token);

		$service = $jgoogleapi->getService('Tasks');
		
		$task = new Google_Task();
		$task -> setTitle($_POST['task']);
		
		$service -> tasks -> insert($_POST["tasklist_id"], $task);
		
		
		Yii::app()->request->redirect($_SERVER['HTTP_REFERER']);
		
	}
	
	public function actionAddtasklist()
	{
		
		
		$user = User::model() -> findByPk($_POST['user_id']);
		if($user === null)
		{
			Yii::app()->request->redirect($_SERVER['HTTP_REFERER']);
		}
		
		
		$jgoogleapi = Yii::app()->JGoogleAPI;
		$client = $jgoogleapi->getClient();
        $client->setAccessToken($user -> token);

		$service = $jgoogleapi->getService('Tasks');
		
		$tasklist = new Google_TaskList();
		$tasklist -> setTitle($_POST['tasklist']);

		$service -> tasklists -> insert($tasklist);
		
		
		Yii::app()->request->redirect($_SERVER['HTTP_REFERER']);
		
	}
	

}