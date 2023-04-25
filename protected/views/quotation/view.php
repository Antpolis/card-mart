<?php
/* @var $this QuotationController */
/* @var $model Quotation */

$this->breadcrumbs=array(
	'Order'=>array('admin'),
	($model->no)?$model->no:'Not generated yet',
);
if(isset($_SESSION['customer']) && $_SESSION['customer']!=1) {
$this->menu=array(
	array('label'=>'Create Quotation', 'url'=>array('create')),
	array('label'=>'Update Quotation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quotation', 'url'=>'#', 'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id,'returnUrl'=>CHtml::normalizeUrl(array())),'confirm'=>'Are you sure you want to delete this item?'),
			'visible'=>($model->status==Quotation::_DRAFT),
			),
	array('label'=>'Manage Quotation', 'url'=>array('admin')),
	array('label'=>'Add Items','url'=>array('addItems','id'=>$model->id),'visible'=>($model->status==Quotation::_DRAFT)),
	array('label'=>'Print Document','url'=>array('print','id'=>$model->id),'visible'=>!($model->status==Quotation::_DRAFT),'linkOptions'=>array('target'=>'_blank'))
);
}
else{
	$this->menu=array(
			array('label'=>'Create Order', 'url'=>array('customerCreate')),
			array('label'=>'View Order', 'url'=>array('view', 'id'=>$model->id)),
			array('label'=>'Manage Order', 'url'=>array('admin')),
			//array('label'=>'Print Document','url'=>array('print','id'=>$model->id),'visible'=>!($model->status==Quotation::_DRAFT),'linkOptions'=>array('target'=>'_blank'))
	);
}
?>

<h1>View Quotation <?php echo ($model->no)?$model->no:'Not generated yet'; ?></h1>

<?php $totalValue = $model->getTotal(); $this->widget('zii.widgets.CDetailView', array(
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
		array(
			'label'=>'Discount Rate',
			'value'=>$totalValue['discRate'],
			'type'=>'raw'
		),
		array(
			'label'=>'Total Discount',
			'value'=>$totalValue['discTotal'],
			'type'=>'raw'
		),
		array(
			'label'=>'Total Quotation Amount',
			'value'=>$totalValue['total'],
			'type'=>'raw'
		),
		'remark',
		'statusName',
	),
)); ?>
<?php if(($model->status == Quotation::_DRAFT && !$model->byClient) || ($model->status == Quotation::_UNCONFIRM && $model->byClient)):?>
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
		array(
			'header'=>'Unit Price',
			'value'=>'"S$ ".Yii::app()->format->formatNumber($data->price)',
		),
		array(
			'header'=>'Total',
			'value'=>'"S$ ".Yii::app()->format->formatNumber($data->price*$data->qty)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'visible'=>(($model->status==Quotation::_DRAFT && !$model->byClient) || $model->status==Quotation::_UNCONFIRM),
			'updateButtonUrl'=>'CHtml::normalizeUrl(array("quotationItem/update","id"=>$data->id))',
			'updateButtonOptions'=>array('onClick'=>'openUpdateItem($(this).attr("href"));return false'),
			'deleteButtonUrl'=>'CHtml::normalizeUrl(array("quotationItem/delete","id"=>$data->id))',
		),
	),
	
)); ?>

<?php if(!$model->byClient):?>
	<?php if($model->status == Quotation::_DRAFT):?>
	
	<div>
		<iframe src="<?php echo CHtml::normalizeUrl(array('quotation/totalCost','id'=>$model->id,'iframe'=>'iframe'));?>"  id="addItemIframe" width="710" frameBorder="0" scrolling="no" onLoad="afterIframeLoad()">
		
		</iframe>
	</div>
	
	<div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'quotation-item-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<div class="row buttons span-15" style="margin: 0 auto;float: none;background: white;border: 1px solid #C9E0ED;">
			<div style="padding:20px;">
			<h3>3) Comfirming quotation.</h3>
			<?php echo CHtml::submitButton('Generate Quotation & Invoice',array('name'=>'generateOrder')); ?>
			</div>
		</div>
		<div style="clear:both;"></div>
		<?php $this->endWidget(); ?>
	</div>
	<?php endif;?>
<?php elseif($model->status == Quotation::_UNCONFIRM):?>
	<div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'quotation-item-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<div class="row buttons span-15" style="margin: 0 auto;float: none;background: white;border: 1px solid #C9E0ED;">
			<div style="padding:20px;">
			<h3>2) Comfirming quotation.</h3>
			<?php echo CHtml::submitButton('Send for confirmation',array('name'=>'confirmOrder')); ?>
			</div>
		</div>
		<div style="clear:both;"></div>
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