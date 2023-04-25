<?php
/* @var $this StockMovementController */
/* @var $model StockMovement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stock-movement-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'itemID'); ?>
		<?php echo $form->textField($model,'itemID'); ?>
		<?php echo $form->error($model,'itemID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'movementType'); ?>
		<?php echo $form->textField($model,'movementType'); ?>
		<?php echo $form->error($model,'movementType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userID'); ?>
		<?php echo $form->textField($model,'userID'); ?>
		<?php echo $form->error($model,'userID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'quotationID'); ?>
		<?php echo $form->textField($model,'quotationID'); ?>
		<?php echo $form->error($model,'quotationID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastModifiedDate'); ?>
		<?php echo $form->textField($model,'lastModifiedDate'); ?>
		<?php echo $form->error($model,'lastModifiedDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastModifiedBy'); ?>
		<?php echo $form->textField($model,'lastModifiedBy'); ?>
		<?php echo $form->error($model,'lastModifiedBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createDate'); ?>
		<?php echo $form->textField($model,'createDate'); ?>
		<?php echo $form->error($model,'createDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createBy'); ?>
		<?php echo $form->textField($model,'createBy'); ?>
		<?php echo $form->error($model,'createBy'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->