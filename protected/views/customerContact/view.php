<?php
/* @var $this CustomerContactController */
/* @var $model CustomerContact */

$this->breadcrumbs=array(
	'Customer Contacts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CustomerContact', 'url'=>array('index')),
	array('label'=>'Create CustomerContact', 'url'=>array('create')),
	array('label'=>'Update CustomerContact', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CustomerContact', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerContact', 'url'=>array('admin')),
);
?>

<h1>View CustomerContact #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userID',
		'contact',
		'lastModifiedDate',
		'lastModifiedBy',
		'createBy',
		'createDate',
	),
)); ?>
