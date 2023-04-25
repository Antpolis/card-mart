<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property integer $id
 * @property string $name
 * @property integer $qty
 * @property string $unitType
 * @property double $costPrice
 * @property double $sellPrice
 * @property string $barcode
 */
class Item extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	
	public $uploadFile = null;
	
	public $unitTypeArray = array(
		'Pack'=>'Pack',
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
		return 'item';
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
	public function rules()	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active','default','value'=>1),
			array('name','unique'),
			array('name', 'required'),
			array('qty,retailQty', 'numerical', 'integerOnly'=>true,'on'=>'intitialStock'),
			array('uploadFile','file','allowEmpty'=>true,'maxSize'=>1024*1024*4,'types'=>'gif,jpg,jpeg,png','wrongType'=>'You can only upload image files'),
			array('costPrice, sellPrice', 'numerical'),
			array('name', 'length', 'max'=>256),
			array('pictureFile', 'length', 'max'=>125),
			array('unitType', 'length', 'max'=>10),
			array('barcode', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, qty, unitType, costPrice, sellPrice, barcode', 'safe', 'on'=>'search'),
		);
	}
	
	public function calculateStock() {
		$stockMovement = StockMovement::model()->findAll('itemID=:itemID and active = 1',array(':itemID'=>$this->id));
		$amt = 0;
		$retailQty = 0;
		foreach($stockMovement as $stockModel) {
			switch($stockModel->movementType) {
				case $stockModel->movementTypeArray['sellStock']:
				case $stockModel->movementTypeArray['missingStock']:
						if($stockModel->stockType==StockMovement::_NORMALSTOCK) 
							$amt -= $stockModel->qty;
						else
							$retailQty -= $stockModel->qty;
					break;
				case $stockModel->movementTypeArray['initialStock']:
				case $stockModel->movementTypeArray['addStock']:
				case $stockModel->movementTypeArray['restock']: 
				default:
					if($stockModel->stockType==StockMovement::_NORMALSTOCK)
						$amt += $stockModel->qty;
					else
						$retailQty += $stockModel->qty;
					break;
			}	
		}
	
		$this->qty = $amt;
		$this->retailQty = $retailQty;
		$this->save();
	}
	
	public function delete() {
		$this->active = 0;
		$this->save();
	}
	
	public function beforeValidate() {
		if($this->uploadFile) {
			$this->pictureFile = $this->uploadFile->getName();
		}
		return parent::beforeValidate();
	}
	
	public function beforeSave() {
		if($this->uploadFile) {
			$savedFileLoc = Yii::getPathOfAlias('application').'/../uploads/';
			
			$file=$this->uploadFile;
			$filename = basename($file->getName(),'.'.$file->getExtensionName());
			$filename = strtolower($filename);
			while (file_exists($savedFileLoc . $filename . '.' . $file->getExtensionName())) {
				$filename .= rand(10, 9999);
			}
			$_filename = $filename. '.' . $file->getExtensionName();
			$savedFileLoc = $savedFileLoc  . $filename . '.' . $file->getExtensionName();
			
			$this->uploadFile->saveAs($savedFileLoc);
			$this->pictureFile = $_filename;
		}
		return parent::beforeSave();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'movement'=>array(self::HAS_MANY,'StockMovement','itemID'),
		);
	}
	
	public function afterSave() {
		switch($this->getScenario()) {
			case 'intitialStock':
					StockMovement::model()->addStock($this->id, $this->qty,$this->unitType,array('movementType'=>StockMovement::model()->movementTypeArray['initialStock']));
					StockMovement::model()->addStock($this->id, $this->retailQty,$this->unitType,array('movementType'=>StockMovement::model()->movementTypeArray['initialStock'],'stockType'=>StockMovement::_RETAILSTOCK));
				break;
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'qty' => 'Distribution Qty',
			'retailQty' => 'Retail Qty',
			'unitType' => 'Packaging Type',
			'costPrice' => 'Cost Price',
			'sellPrice' => 'Sell Price',
			'barcode' => 'Barcode',
			'uploadFile'=>'Item Image'
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('unitType',$this->unitType,true);
		$criteria->compare('costPrice',$this->costPrice);
		$criteria->compare('sellPrice',$this->sellPrice);
		$criteria->compare('barcode',$this->barcode,true);
		$criteria->compare('active', 1);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchStock()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('itemID',$this->id);	
		
		$criteria->order = 'id desc';
		return new CActiveDataProvider(StockMovement::model(), array(
				'criteria'=>$criteria,
		));
	}
}