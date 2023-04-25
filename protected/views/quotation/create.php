<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Order'=>array('admin'),
	'Create',
);

$this->layout = '//layouts/column2-rev';
?>

<h1>Create Quotation</h1>
<?php if($model->customerID):?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php else:?>
<h2> << Choose a customer.</h2>
<?php endif;?>