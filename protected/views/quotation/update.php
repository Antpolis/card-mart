<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Order'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
if(isset($_SESSION['customer']) && $_SESSION['customer'] != 1) {
	$this->menu=array(
			array('label'=>'Create Order', 'url'=>array('create')),
			array('label'=>'View Order', 'url'=>array('view', 'id'=>$model->id)),
			array('label'=>'Delete Order', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
			array('label'=>'Manage Order', 'url'=>array('admin')),
	);
}
else{
	$this->menu=array(
			array('label'=>'Create Order', 'url'=>array('customerCreate')),
			array('label'=>'View Order', 'url'=>array('view', 'id'=>$model->id)),
			array('label'=>'Manage Order', 'url'=>array('admin')),
	);
}
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>