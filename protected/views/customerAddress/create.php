<?php
/* @var $this CustomerAddressController */
/* @var $model CustomerAddress */

$this->breadcrumbs=array(
	'Customer Addresses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CustomerAddress', 'url'=>array('index')),
	array('label'=>'Manage CustomerAddress', 'url'=>array('admin')),
);
?>

<h1>Create CustomerAddress</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>