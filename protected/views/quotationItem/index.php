<?php
/* @var $this QuotationItemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Quotation Items',
);

$this->menu=array(
	array('label'=>'Create QuotationItem', 'url'=>array('create')),
	array('label'=>'Manage QuotationItem', 'url'=>array('admin')),
);
?>

<h1>Quotation Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
