<?php
/* @var $this StockMovementController */
/* @var $data StockMovement */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('itemID')); ?>:</b>
	<?php echo CHtml::encode($data->itemID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('movementType')); ?>:</b>
	<?php echo CHtml::encode($data->movementType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userID')); ?>:</b>
	<?php echo CHtml::encode($data->userID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quotationID')); ?>:</b>
	<?php echo CHtml::encode($data->quotationID); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedBy')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createDate')); ?>:</b>
	<?php echo CHtml::encode($data->createDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createBy')); ?>:</b>
	<?php echo CHtml::encode($data->createBy); ?>
	<br />

	*/ ?>

</div>