<?php
/* @var $this CustomerContactController */
/* @var $model CustomerContact */

$this->breadcrumbs=array(
	'Customer Contacts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CustomerContact', 'url'=>array('index')),
	array('label'=>'Manage CustomerContact', 'url'=>array('admin')),
);
?>

<h1>Create CustomerContact</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>