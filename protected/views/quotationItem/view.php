<?php
/* @var $this QuotationItemController */
/* @var $model QuotationItem */

$this->breadcrumbs=array(
	'Quotation Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List QuotationItem', 'url'=>array('index')),
	array('label'=>'Create QuotationItem', 'url'=>array('create')),
	array('label'=>'Update QuotationItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete QuotationItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuotationItem', 'url'=>array('admin')),
);
?>

<h1>View QuotationItem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'itemID',
		'quotationID',
		'price',
		'qty',
		'lastModifiedDate',
		'lastModifiedBy',
		'createBy',
		'createDate',
		'description',
	),
)); ?>
