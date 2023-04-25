<?php
/* @var $this CustomerContactController */
/* @var $data CustomerContact */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userID')); ?>:</b>
	<?php echo CHtml::encode($data->userID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contact')); ?>:</b>
	<?php echo CHtml::encode($data->contact); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedDate')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastModifiedBy')); ?>:</b>
	<?php echo CHtml::encode($data->lastModifiedBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createBy')); ?>:</b>
	<?php echo CHtml::encode($data->createBy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createDate')); ?>:</b>
	<?php echo CHtml::encode($data->createDate); ?>
	<br />


</div>