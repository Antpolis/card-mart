<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Staffs'=>array('index'),
	$model->username,
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'username',
		'password',
		'role',
	),
)); ?>


<h3>Quotations</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'quotation-grid',
	'dataProvider'=>$quotationModel->getStaffQuotation(),
	'filter'=>$quotationModel,
	'columns'=>array(
		array(
			'header'=>'Quotation No.',
			'value'=>'$data->noLabel',
			'name'=>'no',
		),		
		'documentDate',
		'remark',
		array(
			'header'=>'Total Amount',
			'value'=>'$data->getTotal("total")',
		),
		array(
			'header'=>'Status',
			'value'=>'$data->statusName',
			'filter'=>$quotationModel->getStatusArray(),
			'name'=>'status'
		),		
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'viewButtonUrl'=>'CHtml::normalizeUrl(array("quotation/view","id"=>$data->id))',
			'updateButtonUrl'=>'CHtml::normalizeUrl(array("quotation/update","id"=>$data->id))'
		),
	),
)); ?>