<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $lastModifiedDate
 * @property integer $lastModifiedBy
 * @property integer $createBy
 * @property string $createDate
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
	 */
	
	public $userID=0;
	
	public $oldPassword;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	
	public function tableName()
	{
		return 'customer';
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
			array('active','default','value'=>true),
			array('username','unique'),
			array('name, username', 'required'),
			array('password','required','on'=>'changePassword, register'),
			array('lastModifiedBy, createBy,active', 'numerical', 'integerOnly'=>true),
			array('name, username, password', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, username, password, lastModifiedDate, lastModifiedBy, createBy, createDate, active', 'safe', 'on'=>'search'),
		);
	}
	
	public function getStatus($id) {
		$model = $this->findByPk($id);
		if($model)
			return $model->active;
		return false;
	}
	
	public function afterSave() {
		$this->password = '';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'address'=>array(self::HAS_ONE,'CustomerAddress','userID'),
			'staff'=>array(self::MANY_MANY,'User','userCustomer(customerID,userID)'),
		);
	}
	
	
	public function addStaff($id) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$id,':customerID'=>$this->id);
		$results = Yii::app()->db->getCommandBuilder()->createCountCommand(UserCustomer::model()->tableName(),$criteria)->queryScalar();
		if(!$results)
			$this->dbConnection->commandBuilder->createInsertCommand(UserCustomer::model()->tableName(), array('customerID'=>$this->id,'userID'=>$id))->execute();
	}
	public function replaceStaff($id,$newID) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$id,':customerID'=>$this->id);
		$this->dbConnection->commandBuilder->createDeleteCommand(UserCustomer::model()->tableName(),$criteria)->execute();
		$this->dbConnection->commandBuilder->createInsertCommand(UserCustomer::model()->tableName(), array('customerID'=>$this->id,'userID'=>$newID))->execute();
	}
	public function deleteStaff($id) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$id,':customerID'=>$this->id);
		$this->dbConnection->commandBuilder->createDeleteCommand(UserCustomer::model()->tableName(),$criteria)->execute();
	}
	
	public function delete() {
		$this->active = 0;
		return $this->save();
		
	}
	
	public function beforeSave() {
		$hash = new PasswordHash(8,true);
		switch($this->getScenario()) {
			case 'register':
			case 'changePassword':
				$this->password = $hash->HashPassword($this->password);
				break;
			default:
				if(!$this->isNewRecord)
					$this->password = $this->oldPassword;
				break;
		}
		
		return parent::beforeSave();
	}
	
	public function afterFind() {
		if($this->staff && isset($this->staff[0]->username))
			$this->userID = $this->staff[0]->id;
		
		$this->oldPassword = $this->password;
		
		return parent::afterFind();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'username' => 'Username',
			'password' => 'Password',
			'lastModifiedDate' => 'Last Modified Date',
			'lastModifiedBy' => 'Last Modified By',
			'createBy' => 'Create By',
			'createDate' => 'Create Date',
			'active'=>'Active'
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate,true);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->compare('createBy',$this->createBy);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('active',$this->active,true);
		if($this->userID !== 0) {
			$criteria->join = 'left join userCustomer uc on uc.customerID = t.id';
			$criteria->addCondition('uc.userID=:userID');
			$criteria->params =array_merge($criteria->params,array(':userID'=>$this->userID));
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}