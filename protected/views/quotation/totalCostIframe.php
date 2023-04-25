<?php
/* @var $this QuotationItemController */
/* @var $model QuotationItem */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quotation-item-form',
	'enableAjaxValidation'=>true,
)); ?>
	<h3>2) Discount</h3>
	<div class="row">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate'); ?><?php echo $form->dropDownList($model,'rateType',array(Quotation::_fixedRate=>'Fixed amount',Quotation::_percentRate=>'Percent Rate'),array('style'=>'margin-left:5px;')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php echo $form->error($model,'rate'); ?>
	</div>

<?php $this->endWidget(); ?>
