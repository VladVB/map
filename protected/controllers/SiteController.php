<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
    
        protected function beforeAction($action)
        {           
            $cs = Yii::app()->clientScript;    
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/jquery.js'));
            $cs->registerScriptFile('//maps.googleapis.com/maps/api/js?key=' .Yii::app()->params['googleKey'] .' & sensor=false', CClientScript::POS_END);
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/maplabel.js'), CClientScript::POS_END); 
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/markerclusterer.js'), CClientScript::POS_END); 
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/markerwithlabel.js'), CClientScript::POS_END); 
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/select2.full.js'), CClientScript::POS_END); 
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/i18n/ru.js'), CClientScript::POS_END); 
            $cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/jquery-ui.js'), CClientScript::POS_END);            
                       
            if ($action->id == 'map') {
                $cs->registerCssFile(Yii::app()->request->baseUrl.'/css/non-responsive.css');
            }            
            //показываем форму
            return parent::beforeAction($action);
        } 
    
    
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
        
        public function actionSelect($q, $idp, $modelname)
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
                /*$tablename = 'dic_' .strtolower($modelname);
                if ($idp>0) {
                    $sql="SELECT * FROM " .$tablename ." where id_parent=" .$idp ." and text like '" . $q ."%'";
                } else {
                    $sql="SELECT * FROM " .$tablename ." where text like '" . $q ."%'";
                }
                $connection=Yii::app()->db;
                $dataReader=$connection->createCommand($sql)->query();
                $rows=$dataReader->readAll();  */   
                $rows = $this->getSelect($q, $idp, $modelname);
                echo CJSON::encode($rows); 
	}
        
        public function getSelect($q, $idp, $modelname)
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
                $tablename = 'dic_' .strtolower($modelname);
                if ($idp>0) {
                    $sql="SELECT * FROM " .$tablename ." where id_parent=" .$idp ." and text like '" . $q ."%'";
                } else {
                    $sql="SELECT * FROM " .$tablename ." where text like '" . $q ."%'";
                }
                $connection=Yii::app()->db;
                $dataReader=$connection->createCommand($sql)->query();
                $rows=$dataReader->readAll();  
                return $rows;
                //echo CJSON::encode($rows); 
	}
        
        public function actionAdd($term, $modelname, $id_parent)
	{
                //$model=new Cities;
		//$model->id = $id;
                $model=new $modelname;
                $model->text = $term;
                if ($id_parent >0) {
                    $model->id_parent = $id_parent;
                }
                $model->save();
                $rows = $this->getSelect($term, $id_parent, $modelname);
                //$row = $rows[0];
                if (count($rows)>0) echo CJSON::encode($rows[0]);
                //return $row;                 
                //echo '111';
	}
        public function actionRemove($modelname, $id)
	{
                //$model=new Cities;
		//$model->id = $id;
                $model=new $modelname;
                $post=$model->findByPk($id); // предполагаем, что запись с ID=10 существует
                // удаляем строку из таблицы
                if ($post->delete()) {
                    echo CJSON::encode(array('cod' => 1, 'name' => $modelname));                    
                } else {
                    echo CJSON::encode(array('cod' => -1));
                }
                /*$model->text = $term;
                if ($id_parent >0) {
                    $model->id_parent = $id_parent;
                }
                $model->save();
                $rows = $this->getSelect($term, $id_parent, $modelname);
                //$row = $rows[0];
                if (count($rows)>0) echo CJSON::encode($rows[0]);
                 * 
                 */
                //return $row;                 
                //echo '111';
	}
        
        public function actionUpdate($modelname, $id, $text)
	{
                $model=new $modelname;
                $post=$model->findByPk($id);
                if ($post !== null) {
                    $post->text=$text;
                    $post->save(); // сохраняем изменения                    
                    
                    echo CJSON::encode(array('cod' => 1, 'name' => $modelname, 'id' => $id, 'text' => $text));
                } else {
                    echo CJSON::encode(array('cod' => -1));
                }
        }
        
        
        public function actionData()
	{
		//$sql="SELECT * FROM cities";
                $sql="select CONCAT_WS(',', citie_name,  street_name, h.text) as name, onmap from dic_house h  left join (select c.text as citie_name,  s.text as street_name, s.id as ids from dic_street  s left join  dic_cities c  on c.id = s.id_parent) n on n.ids = h.id_parent";
                $connection=Yii::app()->db;
                $dataReader=$connection->createCommand($sql)->query();
                $rows=$dataReader->readAll();
                echo CJSON::encode($rows);               
	}
        
        public function actionMap()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('map');
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
        
        /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionRegistration()
	{
		$model=new User;
                $model->scenario = 'registration';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save()) {
                            Yii::app()->user->setFlash('registration','Вы можете авторизоваться');
                        }				
		}
		$this->render('registration',array('model'=>$model));
	}
}