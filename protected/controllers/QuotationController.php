<?php

class QuotationController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public $customerModel;
	
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','addItems','print','totalCost'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','customerCreate'),
				'users'=>array('@'),
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
		$model = $this->loadModel($id);
		$itemSearchModel = new QuotationItem;
		$itemSearchModel->quotationID = $id;
		// Uncomment the following line if AJAX validation is needed
		
		if(isset($_POST['generateOrder'])) {
			$model->status = Quotation::_CONFIRM;
			$model->setScenario('generateInvoice');
			$model->save();
		}
		if(isset($_POST['confirmOrder'])) {
			$model->status = Quotation::_DRAFT;
			//$model->setScenario('generateInvoice');
			$model->save();
		}
		if(isset($_SESSION['customer']) && $_SESSION['customer'] == 1)
			$this->layout = '//layouts/custcolumn2';
		$this->render('view',array(
			'model'=>$model,
			'itemSearchModel'=>$itemSearchModel
		));
	}
	
	public function actionPrint($id) {
		$this->layout = '//layouts/print';
		$model = $this->loadModel($id);
		$pdfPages = array();
		$pdfQPages = array();
		list($dataProvider,$totalPages) = $model->getItems(array(),true);
		$body = $this->render('print',array('model'=>$model,'invoice'=>false,'dataProvider'=>$dataProvider,'lastPage'=>(1==$totalPages)),true);
		$bodyDir = $this->makeHeader($body);
		
		$Quotationbody = $this->render('print',array('model'=>$model,'invoice'=>true,'dataProvider'=>$dataProvider,'lastPage'=>(1==$totalPages)),true);
		$QuotationbodyDir = $this->makeHeader($Quotationbody,'qBody');
		
		$footer = $this->renderPartial('printFooter',array('invoice'=>false),true);
		$footerDir = $this->makeHeader($footer,'footer');
		$header = $this->renderPartial('printHeader',array('model'=>$model,'invoice'=>true),true);
		$headerDir = $this->makeHeader($header,'header');
		$Qheader = $this->renderPartial('printHeader',array('model'=>$model,'invoice'=>false),true);
		$QheaderDir = $this->makeHeader($Qheader,'Qheader');
		$pdfPages[] = array(
			'page' => $bodyDir,
			'footer.htmlUrl'=>$footerDir,
			'header.htmlUrl'=>$headerDir,
			'header.space'=>8.9,
			'header.line'=>false,
			'footer.line'=>false,
		);
		$pdfQPages[] = array(
				'page' => $QuotationbodyDir,
				'footer.htmlUrl'=>$footerDir,
				'header.htmlUrl'=>$QheaderDir,
				'header.space'=>8.9,
				'header.line'=>false,
				'footer.line'=>false,
		);
		
		if($totalPages>1) {
			for($i=1;$i<$totalPages;$i++) {
				list($dataProvider,$_totalPages) = $model->getItems(array(),true,$i);
				$body = $this->render('print',array('model'=>$model,'invoice'=>false,'dataProvider'=>$dataProvider,'lastPage'=>(($i+1)==$totalPages)),true);
				$bodyDir = $this->makeHeader($body);	
				$Quotationbody = $this->render('print',array('model'=>$model,'invoice'=>true,'dataProvider'=>$dataProvider,'lastPage'=>(($i+1)==$totalPages)),true);
				$QuotationbodyDir = $this->makeHeader($Quotationbody,'qBody');
				$pdfPages[] = array(
					'page' => $bodyDir,
					'footer.htmlUrl'=>$footerDir,
					'header.htmlUrl'=>$headerDir,
					'header.space'=>8.9,
					'header.line'=>false,
					'footer.line'=>false,
						
				);
				$pdfQPages[] = array(
						'page' => $QuotationbodyDir,
						'footer.htmlUrl'=>$footerDir,
						'header.htmlUrl'=>$QheaderDir,
						'header.space'=>8.9,
						'header.line'=>false,
						'footer.line'=>false,
				);
			}
		}
		
		$fileName = $this->makeFile('DownloadFile').$model->no.'.pdf';
		
		wkhtmltox_convert('pdf',
			array('out' => $fileName, 'imageQuality' => '95','web.printMediaType'=>true
					,'size.paperSize'=>'A4','margin.bottom'=>'3cm',	'margin.top'=>'9cm',
					), // global settings
			array_merge($pdfPages,$pdfQPages)); // object settings
		$this->downloadFile($fileName);
		//$this->render('print',array('model'=>$model,'invoice'=>false));
		
	}
	
	public function downloadFile($fileName) {
		$content = file_get_contents($fileName);
		if(!headers_sent()){
			header('Content-Description: File Transfer');
			header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
			header('Pragma: public');
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			// force download dialog
			header('Content-Type: application/force-download');
			header('Content-Type: application/octet-stream', false);
			header('Content-Type: application/download', false);
			header('Content-Type: application/pdf', false);
			// use the Content-Disposition header to supply a recommended filename
			header('Content-Disposition: attachment; filename="'.basename($fileName).'";');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.strlen($content));
			echo $content;
		}else{
			throw new Exception('WKPDF download headers were already sent.');
		}
		break;
	}
	
	public function actionCustomerCreate() {
		$model=new Quotation;
		$model->userID = 0;
		$this->customerModel = new Customer;
		$model->documentDate = date('d/m/Y');
		$model->customerID = Yii::app()->user->getId();
		$model->byClient = true;
		$model->status = Quotation::_UNCONFIRM;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Quotation']))
		{
			$model->attributes=$_POST['Quotation'];
			$customerModel = Customer::model()->findByPk($model->customerID);
			if($customerModel) {
		
				$model->userID = $customerModel->userID;
			}
				
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		$this->layout = '//layouts/custcolumn2';
		$this->render('customercreate',array(
			'model'=>$model,
		));
	}
	
	public function makeFile($type) {
		$folder = Yii::getPathOfAlias('application').'/data/'.$type.'/';
		$tmp = '';
		if(!is_dir($folder)) {
			if(!mkdir($folder,0777,true))
				throw new CException('Unable to create temp folder');
		}
		return $folder;
	}
	
	public function makeHeader($html,$type="body") {
		$folder = Yii::getPathOfAlias('application').'/data/'.Yii::app()->user->getId().$type.'/';
		$tmp = '';
		if(!is_dir($folder)) {
			if(!mkdir($folder,0777,true))
				throw new CException('Unable to create temp folder');
		}
		 
		do{
			$tmp=$folder.mt_rand().'.html';
		} while(file_exists($tmp));
		file_put_contents($tmp,$html);
		return $tmp;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Quotation;
		
		if(isset($_GET['customerID'])) {
			$model->customerID = $_GET['customerID'];
		}
		$model->userID = Yii::app()->user->getId();
		$this->customerModel = new Customer;
		$model->documentDate = date('d/m/Y');
		if(!User::model()->isAdmin(Yii::app()->user->getId())) {
			$this->customerModel->userID = Yii::app()->user->getId();
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Quotation']))
		{
			$model->attributes=$_POST['Quotation'];
			$customerModel = Customer::model()->findByPk($model->customerID);
			if($customerModel) {
				
				$model->userID = $customerModel->userID;
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionTotalCost($id) {
		$model=$this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		
		if(isset($_POST['Quotation'])) 	{
			$model->attributes = $_POST['Quotation'];
			$model->save();			
		}
		$this->layout = '//layouts/iframe';
		$this->render('totalCostIframe',array(
			'model'=>$model,
		));
	}
	
	public function actionAddItems($id) {
		$model=$this->loadModel($id);
		$this->customerModel = new Customer;
		$itemModel = new QuotationItem('addItem');
		$itemModel->quotationID = $id;
		if($model->byClient)
			$itemModel->setScenario('addItemByClient');
	
		$itemSearchModel = new QuotationItem('search');
		$itemSearchModel->quotationID = $id;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($itemModel);
	
		if(isset($_POST['QuotationItem'])) 	{
			$itemModel->attributes=$_POST['QuotationItem'];
			$itemModel->itemName=$_POST['itemName'];
			if($itemModel->save()) {
				$itemModel = new QuotationItem('addItem');
				$itemModel->quotationID = $id;
				//$this->redirect(array('view','id'=>$model->id));
			}
				
		}
		if(isset($_GET['iframe'])) {
			$this->layout = '//layouts/iframe';
			$this->render('addItemsIframe',array(
					'model'=>$model,
					'itemModel'=>$itemModel,
					'itemSearchModel'=>$itemSearchModel
			));
		}
		else {
			$this->render('addItems',array(
					'model'=>$model,
					'itemModel'=>$itemModel,
					'itemSearchModel'=>$itemSearchModel
			));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		
		if(isset($_POST['Quotation'])) 	{
			$model->attributes=$_POST['Quotation'];
			if($model->save()) {
				
				$this->redirect(array('view','id'=>$model->id));
			}
			
		}
		
		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('Quotation');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Quotation('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Quotation']))
			$model->attributes=$_GET['Quotation'];
		if(!User::model()->isAdmin(Yii::app()->user->getId())) {
			$model->userID = Yii::app()->user->getId();
		}
		if(isset($_SESSION['customer']) && $_SESSION['customer'] == 1) {
			$this->layout = '//layouts/custcolumn2';
			$model->userID = null;
			$model->customerID = Yii::app()->user->getId();
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
		$model=Quotation::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && ($_POST['ajax']==='quotation-form' || $_POST['ajax'] === 'quotation-item-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
