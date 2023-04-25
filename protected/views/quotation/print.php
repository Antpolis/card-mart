<table width="100%" border="0" cellspacing="0" cellpadding="0" class="print" style="margin-top:40px;">
  <tr>
    <td width="70%">
        
	</td>
    <td width="30%" valign="top"></td>
  </tr>
  <tr>
    <td colspan="2">
    	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'quotation-item-grid',
			'dataProvider'=>$dataProvider,
    		'summaryText'=>'',
			'columns'=>array(
				array(
					'header'=>'No.',
					'value'=>'$row+1',
					
				),
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
			),
			
		)); ?>
    
    </td>
  </tr>
  <?php if($lastPage):?>
  <tr>
    <td>&nbsp;</td>
    <td>
    	<?php $totalValue = $model->getTotal();?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="totalAlign">
      <tr>
        <td>Subtotal :</td>
        <td><?php echo $totalValue['subtotal']?></td>
      </tr>
      
      <tr>
        <td>Disc Rate :</td>
        <td><?php echo $totalValue['discRate']?></td>
      </tr>
      
      <tr>
        <td>Disc Total :</td>
        <td><?php echo $totalValue['discTotal']?></td>
      </tr>
      <tr>
        <td>Grand Total :</td>
        <td><?php echo $totalValue['total']?></td>
      </tr>
    </table></td>
  </tr>
  <?php endif;?>
</table>
<?php if((isset($invoice) && !$invoice)&&$lastPage):?>
	<div style="height:80px;border-bottom:1px solid black;width:200px;margin-bottom:20px;">
		Received By,
	
	</div>
<?php endif;?>