<?php

/**
 * This is the model class for table "stockMovement".
 *
 * The followings are the available columns in table 'stockMovement':
 * @property integer $itemID
 * @property integer $qty
 * @property integer $movementType
 * @property string $remark
 * @property integer $id
 * @property integer $userID
 * @property integer $quotationID
 * @property string $lastModifiedDate
 * @property integer $lastModifiedBy
 * @property string $createDate
 * @property integer $createBy
 */
class RetailStockMovement extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StockMovement the static model class
	 */
	
	public $movementTypeArray = array(
		'initialStock'=>0,
		'addStock'=>1,
		'sellStock'=>2,
		'restock'=>3,
		'missingStock'=>4,
		'canceledQuotation'=>5
	);
	
	public $movementTypeArrayForDropDown = array(
			0=>'Initial Stock',
			1=>'Add Stock',
			2=>'Sell Stock',
			3=>'Restock',
			4=>'Missing Stock',
			5=>'Quotation Canceled',
	);
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stockMovement';
	}
	
	public function behaviors() {
		return array(
			'AutoSimpleLogging' => array(
				'class' => 'application.behaviors.AutoSimpleLoggingBehavior',
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qtyType','default','value'=>'Pack'),
			array('movementType','default','value'=>'1'),
			array('itemID, qty, movementType, userID', 'required'),
			array('itemID, qty, movementType, userID, quotationID, lastModifiedBy, createBy', 'numerical', 'integerOnly'=>true),
			array('remark', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('itemID, qty, movementType, remark, id, userID, quotationID, lastModifiedDate, lastModifiedBy, createDate, createBy', 'safe', 'on'=>'search'),
		);
	}
	
	public function removeStock($itemID,$qty,$datas=array()) {
		$model = Item::model()->findByPk($itemID);
		if($model) {
			$defaultData = array(
				'movementType'=>$this->movementTypeArray['sellStock'],
				'userID'=>Yii::app()->user->getId(),
				'quotationID'=>0,
				'remark'=>null,
			);
			$newData = array();
			if(is_array($datas))
				$newData = array_merge($defaultData,$datas);
			else
				$newData = $defaultData;
			
			$newData['itemID'] = $itemID;
			$newData['qty'] = $qty;
			$newData['qtyType'] = $model->unitType;
			$model = new StockMovement;
			$model->attributes = $newData;
			if(!$model->save())
				print_r($model->getErrors());
		}
	}
	
	public function addStock($itemID,$qty,$qtyType='Pack',$datas=array()) {
		$defaultData = array(
			'movementType'=>$this->movementTypeArray['addStock'],
			'userID'=>Yii::app()->user->getId(),
			'quotationID'=>0,
			'remark'=>null,
		);
		$newData = array();
		if(is_array($datas))
			$newData = array_merge($defaultData,$datas);
		else
			$newData = $defaultData;
		
		$newData['itemID'] = $itemID;
		$newData['qty'] = $qty;
		$newData['qtyType'] = $qtyType;
		$model = new StockMovement;
		$model->attributes = $newData;
		if(!$model->save())
			print_r($model->getErrors());
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'quotation'=>array(self::BELONGS_TO,'Quotation','quotationID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'itemID' => 'Item',
			'qty' => 'Qty',
			'movementType' => 'Movement Type',
			'remark' => 'Remark',
			'id' => 'ID',
			'userID' => 'User',
			'quotationID' => 'Quotation',
			'lastModifiedDate' => 'Last Modified Date',
			'lastModifiedBy' => 'Last Modified By',
			'createDate' => 'Create Date',
			'createBy' => 'Create By',
		);
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

		$criteria->compare('itemID',$this->itemID);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('movementType',$this->movementType);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('quotationID',$this->quotationID);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate,true);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('createBy',$this->createBy);
		$criteria->order = 'id desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}