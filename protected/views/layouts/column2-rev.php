<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5">
	<div id="sidebar" style="padding:20px 0px 20px 20px">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'customer-grid',
		'dataProvider'=>$this->customerModel->search(),
		'filter'=>$this->customerModel,
		'columns'=>array(
			array(
				'class'=>'CLinkColumn',
				'labelExpression'=>'$data->name',
				'urlExpression'=>'CHtml::normalizeUrl(array("quotation/create","customerID"=>$data->id))',
				'header'=>'Assigned Outlets'
			),		
		),
	)); ?>
	</div><!-- sidebar -->
</div>
<div class="span-19 last">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>