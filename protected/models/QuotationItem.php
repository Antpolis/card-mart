<?php

/**
 * This is the model class for table "quotationItem".
 *
 * The followings are the available columns in table 'quotationItem':
 * @property integer $id
 * @property integer $itemID
 * @property integer $quotationID
 * @property integer $price
 * @property integer $qty
 * @property integer $lastModifiedDate
 * @property integer $lastModifiedBy
 * @property integer $createBy
 * @property integer $createDate
 * @property string $description
 */
class QuotationItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuotationItem the static model class
	 */
	
	public $itemName = '';
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotationItem';
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
			array('itemID, quotationID, price, qty', 'required'),
			array('itemID, quotationID, qty, lastModifiedBy, createBy', 'numerical', 'integerOnly'=>true),
			
			array('price', 'numerical'),
				array('qty','checkStock','on'=>'addItem'),
				array('qty','checkStock','on'=>'addItemByClient'),
			array('description', 'length', 'max'=>125),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, itemID, quotationID, price, qty, lastModifiedDate, lastModifiedBy, createBy, createDate, description', 'safe', 'on'=>'search'),
		);
	}
	
	public function afterFind() {
		$itemModel = Item::model()->findByPk($this->itemID);
		$this->itemName = $itemModel->name;
	}
	
	public function afterSave() {
		parent::afterSave();
		
		switch($this->getScenario()) {
			case 'addItem':
			case 'addItemByClient':	
				StockMovement::model()->removeStock($this->itemID,$this->qty,array('quotationID'=>$this->quotationID));
				Item::model()->findByPk($this->itemID)->calculateStock();
		}
		return true;
	}
	
	public function checkStock($attr,$params) {
		$model = Item::model()->findByPk($this->itemID);
		if($model) {
			if($model->qty>=$this->$attr) {
				return true;
			}
		}
		if($this->getScenario()=='addItemByClient')
			$this->addError($attr, 'Error code #1. Please contact admin.');
		else
			$this->addError($attr, 'Not enough stock. Current stock left only '.$model->qty);
		return false;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'item'=>array(self::BELONGS_TO,'Item','itemID'),
		);
	}
	
	public function beforeValidate() {
		switch($this->getScenario()) {
			case 'addItemByClient':
			case 'addItem':$this->getItemDefaultValue($this->itemID);
				break;
		}
		return true;
	}
	
	public function getItemDefaultValue($id) {
		$model = Item::model()->findByPk($id);
		if($model) {
			$this->price = $model->sellPrice;
			$this->description = !empty($model->barcode)?$model->barcode.' - '.$model->name:$model->name;
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'itemID' => 'Item',
			'quotationID' => 'Quotation',
			'price' => 'Price',
			'qty' => 'Qty',
			'lastModifiedDate' => 'Last Modified Date',
			'lastModifiedBy' => 'Last Modified By',
			'createBy' => 'Create By',
			'createDate' => 'Create Date',
			'description' => 'Description',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('itemID',$this->itemID);
		$criteria->compare('quotationID',$this->quotationID);
		$criteria->compare('price',$this->price);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('createBy',$this->createBy);
		$criteria->compare('createDate',$this->createDate);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}