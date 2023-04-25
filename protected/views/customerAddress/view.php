<?php
/* @var $this CustomerAddressController */
/* @var $model CustomerAddress */

$this->breadcrumbs=array(
	'Customer Addresses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CustomerAddress', 'url'=>array('index')),
	array('label'=>'Create CustomerAddress', 'url'=>array('create')),
	array('label'=>'Update CustomerAddress', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CustomerAddress', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerAddress', 'url'=>array('admin')),
);
?>

<h1>View CustomerAddress #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userID',
		'address',
		'postal',
		'lastModifiedDate',
		'lastModifiedBy',
		'createDate',
		'createBy',
	),
)); ?>
