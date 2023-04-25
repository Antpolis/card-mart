<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $role
 * @property string $createDate
 * @property integer $createBy
 * @property string $lastModifiedDate
 * @property integer $lastModifiedBy
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	
	const ADMIN = 1;
	const USER = 0;
	
	public $oldPassword = '';
	
	
	public $roleArray = array(User::USER=>'User',User::ADMIN=>'Admin');
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}
	
	public function behaviors() {
		return array(
			'AutoSimpleLogging' => array(
				'class' => 'application.behaviors.AutoSimpleLoggingBehavior',
			),
		);
	}
	
	public function addCustomer($id) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$this->id,':customerID'=>$id);
		$results = Yii::app()->db->getCommandBuilder()->createCountCommand(UserCustomer::model()->tableName(),$criteria)->queryScalar();
		if(!$results)
			$this->dbConnection->commandBuilder->createInsertCommand(UserCustomer::model()->tableName(), array('customerID'=>$id,'userID'=>$this->id))->execute();
	}
	public function replaceCustomer($id,$newID) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$this->id,':customerID'=>$id);
		$this->dbConnection->commandBuilder->createDeleteCommand(UserCustomer::model()->tableName(),$criteria)->execute();
		$this->dbConnection->commandBuilder->createInsertCommand(UserCustomer::model()->tableName(), array('customerID'=>$newID,'userID'=>$this->id))->execute();
	}
	public function deleteCustomer($id) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'userID=:userID and customerID=:customerID';
		$criteria->params = array(':userID'=>$this->id,':customerID'=>$id);
		$this->dbConnection->commandBuilder->createDeleteCommand(UserCustomer::model()->tableName(),$criteria)->execute();
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active','default','value'=>1),
			array('role','default','value'=>self::USER),
			array('username, role, displayName', 'required'),
			array('password','required','on'=>'changePassword, register'),
			array('username','unique'),
			array('role, createBy, lastModifiedBy', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>512),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, role, createDate, createBy, lastModifiedDate, lastModifiedBy', 'safe', 'on'=>'search'),
		);
	}
	
	public function beforeSave() {
		$hash = new PasswordHash(8,true);
		switch($this->getScenario()) {
			case 'register':
			case 'changePassword':	
				$this->password = $hash->HashPassword($this->password);
			
			break;
		}
		if(!$this->isNewRecord)
			$this->password = $this->oldPassword;
		return parent::beforeSave();
	}
	
	public function afterFind() {
		$this->oldPassword = $this->password;
		return parent::afterFind();
	}
	
	public function afterSave() {
		$this->password = '';
	}
	
	public function getAdminUser() {
		$models = $this->findAll('active=:active and role=:role',array(':active'=>true,':role'=>self::ADMIN));
		$returnArray = array();
		foreach($models as $model)
			$returnArray[] = $model->username;
		return $returnArray;
	}
	
	public function getUser() {
		$models = $this->findAll('active=:active',array(':active'=>true));
		$returnArray = array();
		foreach($models as $model)
			$returnArray[] = $model->username;
		return $returnArray;
	}
	
	public function delete() {
		$this->active = 0;
		return $this->save();
	}
	
	public function isAdmin($id) {
		if($this->count('id=:id and role=:role',array(':role'=>self::ADMIN,':id'=>$id)))
			return true;
		else
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'role' => 'Role',
			'createDate' => 'Create Date',
			'createBy' => 'Create By',
			'lastModifiedDate' => 'Last Modified Date',
			'lastModifiedBy' => 'Last Modified By',
		);
	}
	
	public function scopes() {
		return array(
			'activeUsers'=>array(
				'condition'=>'active=1',
			),
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('createBy',$this->createBy);
		$criteria->compare('lastModifiedDate',$this->lastModifiedDate,true);
		$criteria->compare('lastModifiedBy',$this->lastModifiedBy);
		$criteria->addCondition('active=:active');
		$criteria->params = array_merge($criteria->params,array(':active'=>true));
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}