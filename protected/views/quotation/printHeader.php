<table width="100%" border="0" cellspacing="0" cellpadding="0" class="print">
  <tr>
    <td width="70%">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
        	<div class="addressBox">
        		<?php echo Yii::app()->params['companyAddress']?>
        	</div>
        </td>
      </tr>
      <tr>
        <td>
	        <div class="addressBox">
	        	<br />
	        	<strong>Bill To:</strong><br />
	        	<?php echo $model->customer->name;?>
	        	<?php if($model->customer->address):?>
	        		<br />
	        		<?php echo str_replace("\n", "<br />", $model->customer->address->address)?>
	        		<br/>
	        		Singapore <?php echo $model->customer->address->postal?>
	        	<?php endif;?>
        	</div>
        </td>
      </tr>
    </table>
	</td>
    <td width="30%" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr valign="top">
        <td valign="top" style="vertical-align:top;font-size:30px;font-weight:bold;color:#5753f7">
        		<?php if(isset($invoice) && $invoice):?>
        			Invoice
        		<?php else:?>
        			Quotation
        		<?php endif;?>
        	</td>
      </tr>
      <tr>
        <td style="text-align:right">
        	SGCM/<?php echo $model->no;?><br />Date: <?php echo $model->documentDate?><br />
        	Handled By: <?php echo $model->user->displayName?></td>
      </tr>
    </table></td>
  </tr>
 </table>