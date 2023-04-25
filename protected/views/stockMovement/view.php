<?php
/* @var $this StockMovementController */
/* @var $model StockMovement */

$this->breadcrumbs=array(
	'Stock Movements'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StockMovement', 'url'=>array('index')),
	array('label'=>'Create StockMovement', 'url'=>array('create')),
	array('label'=>'Update StockMovement', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StockMovement', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StockMovement', 'url'=>array('admin')),
);
?>

<h1>View StockMovement #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'itemID',
		'qty',
		'movementType',
		'remark',
		'id',
		'userID',
		'quotationID',
		'lastModifiedDate',
		'lastModifiedBy',
		'createDate',
		'createBy',
	),
)); ?>
