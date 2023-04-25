<?php

class CustomerController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','delete','index','view'),
				'users'=>User::model()->getUser(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)	{
		
		$quotationModel = new Quotation('search');
		$quotationModel->unsetAttributes();
		if(isset($_GET['Quotation'])) 
			$quotationModel->attributes = $_GET['Quotation'];
		$quotationModel->customerID = $id;
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'quotationModel'=>$quotationModel
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Customer('register');
		
		$addressModel = new CustomerAddress;
		
		$staffModel = new User;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($addressModel,$model));		
		
		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save()) {
				$addressModel->attributes = $_POST['CustomerAddress'];
				$addressModel->userID = $model->id;
				$addressModel->save();
				$model->addStaff($_POST['User']['id']);
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'addressModel'=>$addressModel,
			'staffModel'=>$staffModel
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model=$this->loadModel($id);
		$model->password = '';
		if($model->address)
			$addressModel = $model->address;
		else
			$addressModel = new CustomerAddress;
		$oldStaff = 0;
		if($model->staff && count($model->staff)) {
			$staffModel = $model->staff[0];
			$oldStaff = $staffModel->id;
		}
		else
			$staffModel = new User;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($addressModel,$model));		
		
		if(isset($_POST['Customer']))
		{
			if(isset($_POST['Customer']['password']) && !empty($_POST['Customer']['password'])) {
				$model->setScenario('changePassword');
			}
			$model->attributes=$_POST['Customer'];
			if($model->save()) {
				$addressModel->attributes = $_POST['CustomerAddress'];
				$addressModel->userID = $model->id;
				if(!$addressModel->save()) {
					print_r($addressModel->getErrors());
					die();
				}
				$model->replaceStaff($oldStaff,$_POST['User']['id']);
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'addressModel'=>$addressModel,
			'staffModel'=>$staffModel
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Customer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model=new Customer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Customer']))
			$model->attributes=$_GET['Customer'];
		if(!User::model()->isAdmin(Yii::app()->user->getId())) {
			$model->userID = Yii::app()->user->getId();
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Customer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
