<?php
/* @var $this QuotationController */
/* @var $model Quotation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quotation-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<?php if(isset($_SESSION['customer']) && $_SESSION['customer'] != 1 && !$model->isNewRecord && $model->status == Quotation::_CONFIRM):?>
	<div class="row">
		<?php echo $form->labelEx($model,'no'); ?>
		<?php echo $form->textField($model,'no'); ?>
		<?php echo $form->error($model,'no'); ?>
	</div>
	<?php endif;?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'documentDate'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>'documentDate',
				'htmlOptions'=>array(
					'class'=>'smallerInput'
				),
				'options'=>array(
					'dateFormat'=>"dd/mm/yy"
				)
			));
		?>
		<?php echo $form->error($model,'documentDate'); ?>
	</div>
	<?php if(isset($_SESSION['customer']) && $_SESSION['customer'] != 1):?>
	<div class="row">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate'); ?><?php echo $form->dropDownList($model,'rateType',array(Quotation::_fixedRate=>'Fixed amount',Quotation::_percentRate=>'Percent Rate'),array('style'=>'margin-left:5px;')); ?>
		<?php echo $form->error($model,'rate'); ?>
	</div>
	<?php endif;?>
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->