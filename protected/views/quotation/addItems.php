<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Quotations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Add items',
);

$this->layout = '//layouts/column2-rev';
?>
<h1>Generating Quotation</h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>'Document No.',
			'value'=>($model->no)?$model->no:'Not generated yet',
		),			
		'documentDate',
		array(
			'label'=>'Customer Name',
			'value'=>CHtml::link($model->customer->name,array('customer/view','id'=>$model->customerID)),
			'type'=>'raw'
		),
		array(
			'label'=>'Staff Name',
			'value'=>CHtml::link($model->user->username,array('user/view','id'=>$model->userID)),
			'type'=>'raw'
		),
		'remark',
		'statusName',
	),
)); ?>
<?php if($model->status == Quotation::_DRAFT):?>
<div>
	<iframe src="<?php echo CHtml::normalizeUrl(array('quotation/addItems','id'=>$model->id,'iframe'=>'iframe'));?>"  id="addItemIframe" width="710" frameBorder="0" scrolling="no" onLoad="afterIframeLoad()">
	
	</iframe>
</div>
<?php endif;?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'quotation-item-grid',
	'dataProvider'=>$itemSearchModel->search(),
	'columns'=>array(
		'qty',
		'description',			
		'price',
		array(
			'header'=>'Total',
			'value'=>'$data->price*$data->qty',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'visible'=>($model->status==Quotation::_DRAFT),
			'updateButtonUrl'=>'CHtml::normalizeUrl(array("quotationItem/update","id"=>$data->id))',
			'updateButtonOptions'=>array('onClick'=>'openUpdateItem($(this).attr("href"));return false'),
			'deleteButtonUrl'=>'',
		),
	),
	
)); ?>
<?php if($model->status == Quotation::_DRAFT):?>
<div>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'quotation-item-form',
		'action'=>array('quotation/view','id'=>$model->id),
		'enableAjaxValidation'=>false,
	)); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Generate Quotation & Invoice',array('name'=>'generateOrder')); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>
<?php endif;?>


<?php 
	$cs = Yii::app()->clientScript;
	$script =<<<EOD
		function openUpdateItem(url) {
			newwindow=window.open(url+'&popup=popup','updateItem','height=500,width=600');
			newwindow.onbeforeunload = function(){ updateGrid()}
			if (window.focus) {newwindow.focus()}
				return false;
			return false;
		}
		function updateGrid() {
			$('#quotation-item-grid').yiiGridView('update');
		}
		
		function afterIframeLoad() {
			updateGrid();
			autoResize('addItemIframe');
		}
		function autoResize(id){
		    var newheight;
		    var newwidth;
		
		    if(document.getElementById){
		        newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;
		        newwidth=document.getElementById(id).contentWindow.document .body.scrollWidth;
		    }
		
		    document.getElementById(id).height= (newheight) + "px";
		    document.getElementById(id).width= (newwidth) + "px";
		}
EOD;
	$cs->registerScript('AddItemScript',$script,CClientScript::POS_END);

?>