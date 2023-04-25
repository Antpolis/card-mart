<?php
/* @var $this QuotationItemController */
/* @var $data QuotationItem */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('itemID')); ?>:</b>
	<?php echo CHtml::encode($data->itemID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quotationID')); ?>:</b>
	<?php echo CHtml::encode($data->quotationID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedBy')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedBy); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('createBy')); ?>:</b>
	<?php echo CHtml::encode($data->createBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createDate')); ?>:</b>
	<?php echo CHtml::encode($data->createDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	*/ ?>

</div>