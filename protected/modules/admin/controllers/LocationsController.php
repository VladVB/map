<?php

class LocationsController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
         /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','select', 'add', 'remove', 'update', 'gettextonmap', 'settextonmap'),
				'roles'=>array('2'),
			),
                        array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        protected function beforeAction($action)
        {           
            $cs = Yii::app()->clientScript;    
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/jquery.js'));
            //Yii::app()->clientScript->registerScriptFile('//maps.googleapis.com/maps/api/js?key=AIzaSyBQFXSUkeyDxeQsqxi5THLAnUEgaSxtUbM & sensor=false', CClientScript::POS_END);
            //Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/maplabel.js'), CClientScript::POS_END); 
            //Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/markerclusterer.js'), CClientScript::POS_END); 
            //Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/markerwithlabel.js'), CClientScript::POS_END); 
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/select2.full.js'), CClientScript::POS_END); 
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/i18n/ru.js'), CClientScript::POS_END); 
            //Yii::app()->assetManager->publish(Yii::app()->basePath . '/../images/');            
            //$cs->registerScriptFile("/js/jquery.js");
            Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/js/jquery-ui.js'), CClientScript::POS_END);            
            //$cs->registerCoreScript('jquerylocal');
            
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
        public function actionGettextonmap($id)
	{
		$model=new House();
                $post=$model->findByPk($id);                 
                if ($post !== null) {
                    echo CJSON::encode(array('cod' => 1, 'onmap' => $post->onmap));                    
                } else {
                    echo CJSON::encode(array('cod' => -1));
                }
	}
        
        public function actionSettextonmap($id, $text)
	{
		$model=new House();
                $post=$model->findByPk($id);                  
                if ($post !== null) {
                    $post->onmap=$text;
                    $post->save(); // сохраняем изменения                     
                    echo CJSON::encode(array('cod' => 1, 'id' => $id, 'onmap' => $text));
                } else {
                    echo CJSON::encode(array('cod' => -1));
                }                 
	}
        
        public function getSelect($q, $idp, $modelname)
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
                $tablename = 'dic_' .strtolower($modelname);
                if ($q !== '') {                
                if ($idp>0) {
                    $sql="SELECT * FROM " .$tablename ." where id_parent=" .$idp ." and text like '" . $q ."%'";
                } else {
                    $sql="SELECT * FROM " .$tablename ." where text like '" . $q ."%'";
                }
                } else {
                if ($idp>0) {
                    $sql="SELECT * FROM " .$tablename ." where id_parent=" .$idp;
                } else {
                    $sql="SELECT * FROM " .$tablename;
                }    
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
        
               


}