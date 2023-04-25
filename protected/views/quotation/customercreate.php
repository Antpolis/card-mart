<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	'Create',
);

?>

<h1>Create Quotation</h1>
<?php echo $this->renderPartial('_customerform', array('model'=>$model)); ?>