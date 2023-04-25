<?php
/* @var $this StockMovementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Stock Movements',
);

$this->menu=array(
	array('label'=>'Create StockMovement', 'url'=>array('create')),
	array('label'=>'Manage StockMovement', 'url'=>array('admin')),
);
?>

<h1>Stock Movements</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
