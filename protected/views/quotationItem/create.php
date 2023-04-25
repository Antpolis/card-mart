<?php
/* @var $this QuotationItemController */
/* @var $model QuotationItem */

$this->breadcrumbs=array(
	'Quotation Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List QuotationItem', 'url'=>array('index')),
	array('label'=>'Manage QuotationItem', 'url'=>array('admin')),
);
?>

<h1>Create QuotationItem</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>