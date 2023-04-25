<?php
/* @var $this ItemController */
/* @var $model Item */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'barcode'); ?>
		<?php echo $form->textField($model,'barcode'); ?>
		<?php echo $form->error($model,'barcode'); ?>
	</div>
	
	<?php if($model->isNewRecord):?>
	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'retailQty'); ?>
		<?php echo $form->textField($model,'retailQty'); ?>
		<?php echo $form->error($model,'retailQty'); ?>
	</div>
	<?php endif;?>
	<div class="row">
		<?php echo $form->labelEx($model,'unitType'); ?>
		<?php echo $form->dropDownList($model, 'unitType', $model->unitTypeArray); ?>
		<?php echo $form->error($model,'unitType'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'costPrice'); ?>
		<?php echo $form->textField($model,'costPrice'); ?>
		<?php echo $form->error($model,'costPrice'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sellPrice'); ?>
		<?php echo $form->textField($model,'sellPrice'); ?>
		<?php echo $form->error($model,'sellPrice'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'uploadFile'); ?>
		<?php echo $form->fileField($model,'uploadFile'); ?>
		<?php echo $form->error($model,'uploadFile'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->