<?php
/* @var $this StockMovementController */
/* @var $model StockMovement */

$this->breadcrumbs=array(
	'Stock Movements'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StockMovement', 'url'=>array('index')),
	array('label'=>'Manage StockMovement', 'url'=>array('admin')),
);
?>

<h1>Create StockMovement</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>