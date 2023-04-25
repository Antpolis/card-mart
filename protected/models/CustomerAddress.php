<?php

/**
 * This is the model class for table "customerAddress".
 *
 * The followings are the available columns in table 'customerAddress':
 * @property integer $id
 * @property integer $userID
 * @property string $address
 * @property string $postal
 * @property string $lastModifiedDate
 * @property integer $lastModifiedBy
 * @property string $createDate
 * @property integer $createBy
 */
class CustomerAddress extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customerAddress';
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
			array('userID, address, postal', 'required'),
			array('userID, lastModifiedBy, createBy', 'numerical', 'integerOnly'=>true),
			array('postal', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userID, address, postal, lastModifiedDate, lastModifiedBy, createDate, createBy', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userID' => 'User',
			'address' => 'Address',
			'postal' => 'Postal',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('userID',$this->userID);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('postal',$this->postal,true);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate,true);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('createBy',$this->createBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}