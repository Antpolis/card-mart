<?php

/**
 * This is the model class for table "quotation".
 *
 * The followings are the available columns in table 'quotation':
 * @property integer $id
 * @property integer $customerID
 * @property integer $userID
 * @property string $documentDate
 * @property string $remark
 * @property string $no
 * @property integer $status
 * @property string $lastModifiedDate
 * @property integer $lastModifiedBy
 * @property string $createDate
 * @property integer $createBy
 * @property integer $rateType
 * @property float $rate
 */
class Quotation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Quotation the static model class
	 */
	
	const _DRAFT = 0;
	const _CONFIRM = 1;
	const _DELIVERED = 2;
	const _CANCEL = 3;
	const _UNCONFIRM = 4;
	
	const _percentRate = 0;
	const _fixedRate = 1;
	
	public $statusName = '';
	
	public $noLabel = '';
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function behaviors() {
		return array(
				'AutoSimpleLogging' => array(
						'class' => 'application.behaviors.AutoSimpleLoggingBehavior',
				),
				'convertDate'=>array(
					'class'=>'application.behaviors.ConvertDateBehavior',
					'col'=>array(
						'documentDate'=>array(
							'documentDate'=>UltiHelper::_RETURNDATE,
						)
					)
				),
		);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('byClient','default','value'=>false),
			array('status','default','value'=>self::_DRAFT),
			array(' rate ','default','value'=>'0.00'),
			array('customerID, userID, documentDate', 'required'),
			array('customerID, userID, status, lastModifiedBy, createBy,rateType', 'numerical', 'integerOnly'=>true),
			array('remark','safe'),
			array('rate ','numerical'),
			array('no', 'length', 'max'=>20,'on'=>'generateInvoice,update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customerID, userID, documentDate, remark, no, status, lastModifiedDate, lastModifiedBy, createDate, createBy,statusName', 'safe', 'on'=>'search'),
		);
	}
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'customer'=>array(self::BELONGS_TO,'Customer','customerID'),
			'user'=>array(self::BELONGS_TO,'User','userID'),
			'items'=>array(self::HAS_MANY,'QuotationItem','quotationID'),
		);
	}
	
	public function getTotal($return=null) {
		$models = QuotationItem::model()->findAll('quotationID=:quotationID',array('quotationID'=>$this->id));
		$returnValue = array(
			'subtotal'=>0,
			'discRate'=>'-',
			'discTotal'=>'-',
			'total'=>0,
		);
		foreach($models as $model) {
			$returnValue['subtotal'] += ($model->price * $model->qty);
			
		}
		$returnValue['total'] = $returnValue['subtotal'];
		if($this->rate) {
			switch($this->rateType) {
				case self::_percentRate: $returnValue['discRate'] = $this->rate . ' %';
					$returnValue['discTotal'] = $returnValue['subtotal'] * ($this->rate/100);
					$returnValue['total'] = $returnValue['subtotal'] - $returnValue['discTotal'];
					$returnValue['discTotal'] = '- S$ '.$returnValue['discTotal'];
					break;
				case self::_fixedRate: $returnValue['discRate'] = '- S$'.$this->rate;
					$returnValue['total'] = $returnValue['subtotal'] - $this->rate;
					$returnValue['discTotal'] = '- S$ '.$this->rate;
					break;
			}
		}
		$returnValue['subtotal'] = 'S$ '. Yii::app()->format->formatNumber($returnValue['subtotal']);
		$returnValue['total'] = 'S$ '. Yii::app()->format->formatNumber($returnValue['total']);
		if($return===null)
			return $returnValue;
		else
			return $returnValue[$return];
	}
	
	public function generateQuotationNo() {
		
		$no = date('Y',$this->getAttrTimeStamp('documentDate'));
		$year = date('Y',$this->getAttrTimeStamp('documentDate'));
		
		$criteria = new CDbCriteria;
		$criteria->condition = 'year(documentDate)=:year and status<>:status and status<>:newStatus';
		$criteria->params = array(':year'=>$year,':status'=>self::_DRAFT,':newStatus'=>self::_UNCONFIRM);
		//$criteria->select = array('max(no) as n')
		$count = $this->count($criteria);
		
		
		do {
			$count += 1;
			$criteria = new CDbCriteria;
			$criteria->condition = '`no`=:no';
			$criteria->params = array(':no'=>$no.str_pad($count, 5,'0',STR_PAD_LEFT));
		}
		while($this->count($criteria));
		return $no.str_pad($count, 5,'0',STR_PAD_LEFT);
	}
	
	public function beforeSave() {
		parent::beforeSave();
		switch($this->getScenario()) {
			case 'generateInvoice': $this->no = $this->generateQuotationNo();
				break;
		}
		return true;
	}
	
	public function getStatusArray() {
		return array(
			self::_CONFIRM=>'Confirm Order',
			self::_DELIVERED=>'Order Delivered',
			self::_DRAFT=>'Pending Draft',
			self::_CANCEL=>'Canceled',
			self::_UNCONFIRM=>'UnConfirmed order',
		);
	}
	
	public function afterFind() {
		$statusName = $this->getStatusArray();
		if(isset($statusName[$this->status])) {
			$this->statusName = $statusName[$this->status];
		}
		else 
			$this->statusName = $statusName[self::_DRAFT];
		
		if($this->no) {
			$this->noLabel = $this->no;
		}
		else
			$this->noLabel = 'Pending Quotation';
		return parent::afterFind();
	}
	
	public function delete() {
		if($this->status <> self::_DRAFT && $this->status <> self::_UNCONFIRM) {
			return false;
		}
		foreach($this->items as $itemModel) {
			StockMovement::model()->addStock($itemModel->itemID,$itemModel->qty,'Pack',array('movementType'=>StockMovement::model()->movementTypeArray['canceledQuotation']));
			Item::model()->findByPk($itemModel->itemID)->calculateStock();
		}
		$this->status = self::_CANCEL;
		return $this->save();
	}
	
	public function getItems($searchItem = array(),$forPrint = false,$page=0) {
		$criteria=new CDbCriteria;
		$criteria->compare('quotationID',$this->id);
		if(isset($searchItem['price']))
			$criteria->compare('price',$searchItem['price']);
		if(isset($searchItem['qty']))
			$criteria->compare('qty',$searchItem['qty']);
		if(isset($searchItem['description']))
			$criteria->compare('description',$searchItem['description'],true);
		if(!$forPrint) {
			$csort = new CSort();
		}
		else
			$csort = false;
		
		$count = QuotationItem::model()->count($criteria);
		$pages=new CPagination($count);
		$pages->pageSize = 12;
		$pages->setCurrentPage($page);
		
		$pages->applyLimit($criteria);
		return array(new CActiveDataProvider(QuotationItem::model(), array(
				'criteria'=>$criteria,
				'sort'=>$csort,
				'pagination'=>false,
		)),$pages->getPageCount());
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customerID' => 'Customer',
			'userID' => 'User',
			'documentDate' => 'Document Date',
			'remark' => 'Remark',
			'no' => 'No',
			'status' => 'Status',
			'lastModifiedDate' => 'Last Modified Date',
			'lastModifiedBy' => 'Last Modified By',
			'createDate' => 'Create Date',
			'createBy' => 'Create By',
		);
	}
	
	public function getCustomerQuotation() {
		$criteria=new CDbCriteria;
	
		$criteria->compare('customerID',$this->customerID);
		$criteria->compare('documentDate',$this->documentDate,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('no',$this->no,true);
		$criteria->compare('status',$this->status);
		$criteria->order = 'documentDate desc';
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function getStaffQuotation() {
		$criteria=new CDbCriteria;
	
		$criteria->compare('userID',$this->userID);
		$criteria->compare('documentDate',$this->documentDate,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('no',$this->no,true);
		$criteria->compare('status',$this->status);
		$criteria->order = 'documentDate desc';
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customerID',$this->customerID);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('documentDate',$this->documentDate,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('no',$this->no,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate,true);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('createBy',$this->createBy);
		$criteria->order = 'documentDate desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}