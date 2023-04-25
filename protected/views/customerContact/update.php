<?php
/* @var $this CustomerContactController */
/* @var $model CustomerContact */

$this->breadcrumbs=array(
	'Customer Contacts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CustomerContact', 'url'=>array('index')),
	array('label'=>'Create CustomerContact', 'url'=>array('create')),
	array('label'=>'View CustomerContact', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CustomerContact', 'url'=>array('admin')),
);
?>

<h1>Update CustomerContact <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>