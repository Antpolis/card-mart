<?php
/* @var $this CustomerAddressController */
/* @var $model CustomerAddress */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-address-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'userID'); ?>
		<?php echo $form->textField($model,'userID'); ?>
		<?php echo $form->error($model,'userID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postal'); ?>
		<?php echo $form->textField($model,'postal',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'postal'); ?>
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