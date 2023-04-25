<?php
/* @var $this CustomerAddressController */
/* @var $model CustomerAddress */

$this->breadcrumbs=array(
	'Customer Addresses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CustomerAddress', 'url'=>array('index')),
	array('label'=>'Create CustomerAddress', 'url'=>array('create')),
	array('label'=>'View CustomerAddress', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CustomerAddress', 'url'=>array('admin')),
);
?>

<h1>Update CustomerAddress <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>