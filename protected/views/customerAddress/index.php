<?php
/* @var $this CustomerAddressController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Customer Addresses',
);

$this->menu=array(
	array('label'=>'Create CustomerAddress', 'url'=>array('create')),
	array('label'=>'Manage CustomerAddress', 'url'=>array('admin')),
);
?>

<h1>Customer Addresses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
