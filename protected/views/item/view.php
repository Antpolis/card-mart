<?php
/* @var $this ItemController */
/* @var $model Item */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Item', 'url'=>array('create')),
	array('label'=>'Update Item', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Item', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Item', 'url'=>array('admin')),
);
?>

<h1>View Item #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'qty',
		'retailQty',
		'unitType',
		array(
			'name'=>'costPrice',
			'value'=>"S$ ".Yii::app()->format->formatNumber($model->costPrice),
		),
		array(
			'name'=>'costPrice',
			'value'=>"S$ ".Yii::app()->format->formatNumber($model->sellPrice),
		),
		array(               // related city displayed as a link
			'name'=>'pictureFile',
			'label'=>'Image',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::image(Yii::app()->getBaseUrl(true)."/uploads/".$model->pictureFile),
					Yii::app()->getBaseUrl(true)."/uploads/".$model->pictureFile,array('target'=>'_blank','class'=>'smallImage')),
		),
	),
)); ?>
<br />
<h1>Stock movement</h1>
<hr />
<?php echo CHtml::link('Add stock movement','#',array('id'=>'addNewStock'));?>
<div id="addStock">
	<?php $this->renderPartial('_addStockform',array('model'=>$stockModel));?>
	<hr />
</div>
<div id="stockMovement">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'stock-movement-grid',
		'dataProvider'=>$stockSearchModel->search(),
		'filter'=>$stockSearchModel,
		'columns'=>array(
			array(
				'value'=>'StockMovement::model()->stockTypeArrayForDropDown[$data->stockType]',
				'header'=>'Stock Type',
				'filter'=>$stockSearchModel->stockTypeArrayForDropDown,
				'name'=>'stockType',
			),
			array(
				'value'=>'StockMovement::model()->movementTypeArrayForDropDown[$data->movementType]',
				'header'=>'Stock Movement Type',
				'filter'=>$stockSearchModel->movementTypeArrayForDropDown,
				'name'=>'movementType',
			),
			'qty',
				
			'remark',			
			/*
			
			'lastModifiedDate',
			'lastModifiedBy',
			'createDate',
			'createBy',
			*/
			array(
				'class'=>'CButtonColumn',
				'template'=>'{Quotation} {update}',
				'buttons'=>array(
					'Quotation'=>array(
						'label'=>'Quotation',
						'url'=>'CHtml::normalizeUrl(array("/quotation/view","id"=>$data->quotationID))',
						'visible'=>'$data->quotationID',
						'imageUrl'=>Yii::app()->getBaseUrl().'/images/document.jpg',
					),
					'update'=>array(
						'visible'=>'!$data->quotationID',
					),
				),
			),
		),
	)); ?>
</div>
<?php 
$cs = Yii::app()->clientScript;
$script =<<<EOD
$('#addNewStock').click(function() {
	$('#addStock').toggle();
	return false;
});
EOD;
$cs->registerScript('addStockScript',$script);
?>