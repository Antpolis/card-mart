<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	
	public function makeErrorMsg($error = array(),$header=null,$footer=null,$htmlOptions=array()) {
		$content='';
		foreach($error as $err) {
			if($err!='')
				$content.="<li>$err</li>";
		}
		if($content!=='') {
			if($header===null)
				$header='<p>'.Yii::t('yii','Please fix the following input errors:').'</p>';
			if(!isset($htmlOptions['class']))
				$htmlOptions['class']=CHtml::$errorSummaryCss;
			return CHtml::tag('div',$htmlOptions,$header."<ul>$content</ul>".$footer);
		}
		else
			return '';
	}
	
	public function validateModels($models = array(),$replacement = array(),$endreplacement = array()){
		$error = false;
		$errorAttri = array();
		$errorSummary = array();
		if($models instanceof CActiveRecord)
			$models = array($models);
		foreach($models as $model){
			if($model instanceof CActiveRecord) {
				if(!$model->validate()) {
					$error = true;
					foreach($model->getErrors() as  $name=>$err) {
						if(isset($replacement[$name]))
							$name = $replacement[$name];
						$errorAttri[CHtml::getIdByName(CHtml::resolveName($model, $name))]=$err[0];
						$errorSummary[]=$err[0];
					}
				}
			}
			elseif(is_array($model)) {
				foreach($model as $key=>$_model) {
					if($_model instanceof CActiveRecord) {
						if(!$_model->validate()) {
							$error = true;
							foreach($_model->getErrors() as  $name=>$err) {
								if(isset($replacement[$name]))
									$name = $replacement[$name];
								$name = '['.$key.']'.$name;
								$errorAttri[CHtml::getIdByName(CHtml::resolveName($_model, $name))]=$err[0];
								$errorSummary[]=$err[0];
							}
						}
					}
				}
			}
		}
		if($error) {
			foreach($endreplacement as $key=>$value){
				if(isset($errorAttri[$key])) {
					$errorAttri[$value] = $errorAttri[$key];
					unset($errorAttri[$key]);
				}
			}
			if(Yii::app()->request->isAjaxRequest) {
				echo CJavaScript::jsonEncode(array('attr'=>$errorAttri,'summary'=>$this->makeErrorMsg($errorSummary)));
				Yii::app()->end();
			}
			return false;
		}
		return true;
	}
}