<?php
/* @var $this StockMovementController */
/* @var $model StockMovement */

$this->breadcrumbs=array(
	'Stock Movements'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StockMovement', 'url'=>array('index')),
	array('label'=>'Create StockMovement', 'url'=>array('create')),
	array('label'=>'View StockMovement', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StockMovement', 'url'=>array('admin')),
);
?>

<h1>Update StockMovement <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>