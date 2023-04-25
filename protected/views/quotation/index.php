<?php
/* @var $this QuotationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Quotations',
);

$this->menu=array(
	array('label'=>'Create Quotation', 'url'=>array('create')),
	array('label'=>'Manage Quotation', 'url'=>array('admin')),
);
?>

<h1>Quotations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
