<?php
/* @var $this ItemController */
/* @var $model Item */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Item', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('item-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?> 

<h1>Manage Items</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'item-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'labelExpression'=>'CHtml::image(Yii::app()->getBaseUrl(true)."/uploads/".$data->pictureFile)',
			'urlExpression'=>'Yii::app()->getBaseUrl(true)."/uploads/".$data->pictureFile',
			'linkHtmlOptions'=>array('target'=>'_blank')
		),
		'name',
		'qty',
		'retailQty',
		array(
			'name'=>'unitType',
			'filter'=>$model->unitTypeArray,
		),
		array(
			'name'=>'costPrice',
			'value'=>'"S$ ".Yii::app()->format->formatNumber($data->costPrice)',
			'visible'=>User::model()->isAdmin(Yii::app()->user->getId()),
		),
		array(
			'name'=>'sellPrice',
			'value'=>'"S$ ".Yii::app()->format->formatNumber($data->sellPrice)',
		),
		/*
		'barcode',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
