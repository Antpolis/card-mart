<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Staffs'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'displayName',
		array(
			'header'=>'Role',
			'value'=>'$data->roleArray[$data->role]',
			'name'=>'role',
			'filter'=>$model->roleArray,
		),
		/*
		'lastModifiedDate',
		'lastModifiedBy',
		*/
		array(
			'class'=>'CButtonColumn',
			
		),
	),
)); ?>
