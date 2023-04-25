<?php
class ConvertDateBehavior extends CActiveRecordBehavior {
	
	
	
	public $format =array(
		UltiHelper::_RETURNDATE=>'dd/MM/yyyy',
		UltiHelper::_RETURNDATETIME=>'dd/MM/yyyy HH:mm:ss',
		UltiHelper::_RETURNTIME=>'HH:mm:ss',
	);
	
	private $dateTimeAttr = array();
	
	private $odateTimeAttr = array();
	
	private $colTimeStamp = array();
	
	private $setAttr = array();
	
	public $col = array();
	
	private $dateModel = null;
	
	public $locale = 'en-SG';
	
	public function afterFind($event) {
		$tableSchema = $this->owner->getTableSchema();
		$columns = $tableSchema->columns;
		foreach($columns as $column) {
			$this->setAttrTimeStamp($column);
		}
		foreach($this->col as $colname=>$returnTypes) {
			$checkCol = $colname;
			if(isset($this->colTimeStamp[$colname])) {
				if(is_array($returnTypes)) {
					foreach($returnTypes as $key=>$returnType) {
						if(isset($this->owner->$key)) {
							$this->owner->$key = $this->getValue($colname, $returnType['format']);
						}
					}
				}
				else {
					if(isset($this->owner->$colname)) {
						$this->owner->$colname = $this->getValue($colname, $returnTypes['format']);
					}
				}					
			}			
		}
	}
	
	public function attach($component) {
		$newCol = array();
		foreach($this->col as $colname=>$returnTypes) {
			if(is_array($returnTypes)) {
				foreach($returnTypes as $key=>$returnType) {
					$newCol[$colname][$key] = $this->formatValue($returnType);
				}
			}
			else {
				$newCol[$colname][$colname] = $this->formatValue($returnTypes); // This is to make consistance array
			}
		}
		
		$this->col = $newCol;
		if(!$this->dateModel) {
			$this->dateModel = new CDateFormatter($this->locale);
		}
		return parent::attach($component);
	}
		
	public function formatValue($value) {
		$returnValue = array();
		if(!is_array($value)) {
			$value = array($value);
		}
		if(!isset($value['format'])) {
			switch($value[0]) {
				case UltiHelper::_RETURNDATE:return array($value[0],'format'=>$this->format[UltiHelper::_RETURNDATE]);
					break;
				case UltiHelper::_RETURNTIME:return array($value[0],'format'=>$this->format[UltiHelper::_RETURNTIME]);
					break;
				case UltiHelper::_RETURNDATETIME:return array($value[0],'format'=>$this->format[UltiHelper::_RETURNDATETIME]);
					break;
			}
		}
		else {
			return $value;
		}
	}
	
	private function getValue($col,$type) {
		if(isset($this->colTimeStamp[$col]))
			return $this->dateModel->format($type,$this->colTimeStamp[$col]);
		else
			return '';
	}
	
	public function getFullDate($attr) {
		$timeStamp = $this->getAttrTimeStamp($attr);
		return date('l, F d, Y');
	}
	
	private function setUserAttrTimeStamp($col,$type) {
		switch($type) {
			case Types::RETURNDATE:return CDateTimeParser::parse($col,'dd/MM/yyyy');
				break;
			case Types::RETURNDATETIME:return CDateTimeParser::parse($col,'dd/MM/yyyy HH:mm:ss');
				break;
			case Types::RETURNTIME:return CDateTimeParser::parse($col,'HH:mm:ss');
				break;
			default:return time();
				break;
		}
	}
	
	private function setAttrTimeStamp($col) {
		switch($col->dbType) {
			case 'date':$this->colTimeStamp[$col->name] = CDateTimeParser::parse($this->owner->{$col->name},'yyyy-MM-dd');
				break;
			case 'datetime':
			case 'timestamp':$this->colTimeStamp[$col->name] = CDateTimeParser::parse($this->owner->{$col->name},'yyyy-MM-dd HH:mm:ss');
				break;
		}
	}
	
	private function returnSQLDateTime($col,$time) {
		switch($col->dbType) {
			case 'date': return date('Y-m-d',$time);
				break;
			case 'time':return date('H:i:s',$time);
				break;
			case 'datetime':
			case 'timestamp':return date('Y-m-d H:i:s',$time);
				break;
		}
	}
	
	public function getAttrTimeStamp($attr) {
		if(isset($this->colTimeStamp[$attr]))
			return $this->colTimeStamp[$attr];
		else
			return time();
	}
	
	
	public function beforeSave($event) {
		$tableSchema = $this->owner->getTableSchema();	
		foreach($this->col as $colname=>$returnTypes) {
			if(is_array($returnTypes)) {
				$combinedTime = array(
						'm'=>0,
						'h'=>0,
						'i'=>0,
						'd'=>0,
						'M'=>0,
						'y'=>0,
				);
				foreach($returnTypes as $key=>$returnType) {
					if(isset($this->owner->$key))
						$this->odateTimeAttr[$key] = $this->owner->$key;
					if($returnType[0] == UltiHelper::_RETURNDATETIME) {
						$this->odateTimeAttr[$colname] = $this->owner->$colname;
						$timestamp = CDateTimeParser::parse($this->owner->$colname,$returnType['format']);// $this->setUserAttrTimeStamp($this->owner->$colname,Types::RETURNDATETIME);
						$sqlCol = $tableSchema->getColumn($colname);
						if($sqlCol) {
							$this->owner->$colname = $this->returnSQLDateTime($sqlCol,$timestamp);
						}
					}
					else {
						if($returnType[0] == UltiHelper::_RETURNTIME && isset($this->owner->{$key})) {
							$__time = explode(':', $this->owner->{$key});
							//if(count($__time)==3) {
							$combinedTime['h'] = isset($__time[0])?$__time[0]:0;
							$combinedTime['m'] = isset($__time[1])?$__time[1]:0;
							$combinedTime['i'] = isset($__time[2])?$__time[2]:0;
							//}
								
						}
						//echo $this->owner->{$key};
						if($returnType[0] == UltiHelper::_RETURNDATE && isset($this->owner->{$key})) {
							$__time = explode('/', $this->owner->{$key});
							$combinedTime['d'] = isset($__time[0])?$__time[0]:0;
							$combinedTime['M'] = isset($__time[1])?$__time[1]:0;
							$combinedTime['y'] = isset($__time[2])?$__time[2]:0;
					
						}
						$timestamp = mktime($combinedTime['h'],$combinedTime['m'],$combinedTime['i'],$combinedTime['M'],$combinedTime['d'],$combinedTime['y']);
						$sqlCol = $tableSchema->getColumn($colname);
						if($sqlCol) {
							$this->owner->$colname = $this->returnSQLDateTime($sqlCol,$timestamp);
						}
					}
				}	
			}
			else {
				if(isset($this->owner->$colname)) {
					$this->odateTimeAttr[$colname] = $this->owner->$colname;
					$timestamp = CDateTimeParser::parse($this->owner->$colname,$returnTypes['format']);// $this->setUserAttrTimeStamp($this->owner->$colname,Types::RETURNDATETIME);
					$sqlCol = $tableSchema->getColumn($colname);
					if($sqlCol) {
						$this->owner->$colname = $this->returnSQLDateTime($sqlCol,$timestamp);
					}
				}
			}
		}
		return true;
	}
	
	public function afterSave($event) {
		foreach($this->col as $colname=>$returnTypes) {
			if(is_array($returnTypes)) {
				foreach($returnTypes as $key=>$returnType) {
					if(isset($this->owner->$key))
						$this->owner->$key = $this->odateTimeAttr[$key];
				}				
			}
			else {
				if(isset($this->owner->$colname)) {
					$this->owner->$colname = $this->odateTimeAttr[$colname];					
				}
			}
		}
	}
}