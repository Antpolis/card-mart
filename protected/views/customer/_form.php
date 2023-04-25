<?php
/* @var $this CustomerController */
/* @var $model Customer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($staffModel,'id'); ?>
		<?php echo $form->dropDownList($staffModel,'id',CHtml::listData(User::model()->activeUsers()->findAll(), 'id', 'username')); ?>
		<?php echo $form->error($staffModel,'id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($addressModel,'address'); ?>
		<?php echo $form->textArea($addressModel,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($addressModel,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($addressModel,'postal'); ?>
		<?php echo $form->textField($addressModel,'postal',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($addressModel,'postal'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->