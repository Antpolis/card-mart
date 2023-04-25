<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	
	public $userID = 0;
	
	public function authenticate()
	{
		$model = User::model()->find('username=:username and active=1',array(':username'=>$this->username));
		$hash = new PasswordHash(8,true);
		$session = Yii::app()->session;
		$session['customer']= 0;
		if(!$model)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$hash->CheckPassword($this->password, $model->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
			$this->userID = $model->id;
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	public function getId() {
		return $this->userID;
	}
}