<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Staffs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Create User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>