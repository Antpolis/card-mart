<?php
/* @var $this CustomerContactController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Customer Contacts',
);

$this->menu=array(
	array('label'=>'Create CustomerContact', 'url'=>array('create')),
	array('label'=>'Manage CustomerContact', 'url'=>array('admin')),
);
?>

<h1>Customer Contacts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
