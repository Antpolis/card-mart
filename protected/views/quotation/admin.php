<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Order'=>array('admin'),
	'Manage',
);
if(isset($_SESSION['customer']) && $_SESSION['customer']!=1) {
$this->menu=array(
	array('label'=>'Create Quotation', 'url'=>array('create')),
);
}
else {
	$this->menu=array(
			array('label'=>'Create Quotation', 'url'=>array('customerCreate')),
	);
}
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('quotation-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Orders</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'quotation-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'documentDate',
		'no',
		array(
			'header'=>'Handled By',
			'labelExpression'=>'($data->user)?$data->user->username:"Unknown User"',
			'class'=>'CLinkColumn',
			'urlExpression'=>'CHtml::normalizeUrl(array("user/view","id"=>$data->userID))',
		),
		array(
			'header'=>'Customer',
			'labelExpression'=>'($data->customer)?$data->customer->name:"Unknown Customer"',
			'class'=>'CLinkColumn',
			'urlExpression'=>'CHtml::normalizeUrl(array("customer/view","id"=>$data->customerID))',
		),
		array(
			'header'=>'Status',
			'value'=>'$data->statusName',
			'filter'=>$model->getStatusArray(),
			'name'=>'status'
		),
		'remark',
		/*
		'status',
		'lastModifiedDate',
		'lastModifiedBy',
		'createDate',
		'createBy',
		*/
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'delete'=>array(
					'visible'=>'(($data->status==Quotation::_DRAFT || $data->status==Quotation::_UNCONFIRM) && $_SESSION["customer"] != 1)'
				),
				'update'=>array(
					'visible'=>'($data->status!=Quotation::_CANCEL)'	
				),
			),
		),
	),
)); ?>
