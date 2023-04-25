<?php
class AutoSimpleLoggingBehavior extends CActiveRecordBehavior {
	public function beforeSave($validate) {
		if($this->Owner->hasAttribute('lastModifiedDate'))
			$this->Owner->lastModifiedDate = date('Y-m-d H:i:s');
		if($this->Owner->hasAttribute('lastModifiedBy'))
			if(isset(Yii::app()->user) && Yii::app()->user->getId())
				$this->Owner->lastModifiedBy = Yii::app()->user->getId();
		if($this->Owner->isNewRecord) {
			if($this->Owner->hasAttribute('createBy') && empty($this->Owner->createBy) && Yii::app()->user->getId())
				$this->Owner->createBy = Yii::app()->user->getId();
			if($this->Owner->hasAttribute('createDate') && empty($this->Owner->createDate))
				$this->Owner->createDate = date('Y-m-d H:i:s');
		}	
	}
	
	public function getValues($createBy=0,$lastModifiedBy=0) {		
		return array(
			'createBy'=>($createBy>0)?$createBy:Yii::app()->user->getId(),
			'lastModifiedBy'=>($lastModifiedBy>0)?$lastModifiedBy:Yii::app()->user->getId(),
			'createDate'=>date('Y-m-d H:i:s'),
			'lastModifiedDate'=>date('Y-m-d H:i:s'),
		);
	}
}