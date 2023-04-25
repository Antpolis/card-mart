<?php
class UltiHelper {
	const _OBJECT = 0;
	
	const _ARRAY = 1;
	
	const _DATAPROVIDER = 2;
	
	const _PendingEmailActivation = 3;
	
	const _ActiveEmail = 1;
	
	const _InactiveEmail = 0;
	
	const _removedEmail = 2;
	
	const _RETURNDATE = 0;
	
	const _RETURNDATETIME = 1;
	
	const _RETURNTIME = 2;
	
	public static function getTimeArray($twentyFourHour=false,$hourly=false) {
		$returnArray = array();
		$max = 12;
		if($twentyFourHour)
			$max =24;
		
		for($i=1;$i<=$max;$i++) {
			$returnArray[$i.':00'] = $i.':00';
			if(!$hourly)
				$returnArray[$i.':30'] = $i.':30';
		}
		return $returnArray;
	} 
	
	public static function makeHash($value) {
		return md5($value.time());
	}
	
	public static function sendEmail($view,$subject,$email,$params=array()) {
		$message = new YiiMailMessage;
		$message->view = $view;
		$message->setBody($params, 'text/html');
		$message->subject = $subject;
		$message->addTo($email);
		//$message->addTo('monkeymon@gmail.com');
		$message->setFrom(array('admin@antpolis.com' => 'Antpolis Team'));
		Yii::app()->mail->send($message);
	}
}