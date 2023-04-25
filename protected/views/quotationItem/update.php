<?php
/* @var $this QuotationItemController */
/* @var $model QuotationItem */

$this->breadcrumbs=array(
	'Quotation Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List QuotationItem', 'url'=>array('index')),
	array('label'=>'Create QuotationItem', 'url'=>array('create')),
	array('label'=>'View QuotationItem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage QuotationItem', 'url'=>array('admin')),
);
?>

<h1>Update Quotation Item <?php echo $model->item->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>