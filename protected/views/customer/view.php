<?php
/* @var $this CustomerController */
/* @var $model Customer */

$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'Update Customer', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Customer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>View Customer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'username',
		'address.address',
		'address.postal'
	),
)); ?>
<h3>Quotation</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'quotation-grid',
	'dataProvider'=>$quotationModel->getCustomerQuotation(),
	'filter'=>$quotationModel,
	'columns'=>array(		
		'no',	
		'documentDate',
		array(
			'header'=>'Handled By',
			'labelExpression'=>'$data->user->username',
			'class'=>'CLinkColumn',
			'urlExpression'=>'CHtml::normalizeUrl(array("user/view","id"=>$data->userID))',
		),
		'remark',
		array(
			'header'=>'Status',
			'value'=>'$data->statusName',
			'filter'=>$quotationModel->getStatusArray(),
			'name'=>'status'
		),		
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update} {delete}',
			'updateButtonUrl'=>'CHtml::normalizeUrl(array("quotation/update","id"=>$data->id))',
			'viewButtonUrl'=>'CHtml::normalizeUrl(array("quotation/view","id"=>$data->id))',
			'deleteButtonUrl'=>'CHtml::normalizeUrl(array("quotation/delete","id"=>$data->id))',
			'buttons'=>array(
				'delete'=>array(
					'url'=>'CHtml::normalizeUrl(array("quotation/delete","id"=>$data->id))',       // a PHP expression for generating the URL of the button
				    'visible'=>'!$data->status',   // a PHP expression for determining whether the button is visible
				)
			),
		),
	),
)); ?>