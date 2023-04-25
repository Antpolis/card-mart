<?php
/* @var $this ItemController */
/* @var $data Item */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unitType')); ?>:</b>
	<?php echo CHtml::encode($data->unitType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('costPrice')); ?>:</b>
	<?php echo CHtml::encode($data->costPrice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sellPrice')); ?>:</b>
	<?php echo CHtml::encode($data->sellPrice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('barcode')); ?>:</b>
	<?php echo CHtml::encode($data->barcode); ?>
	<br />


</div>