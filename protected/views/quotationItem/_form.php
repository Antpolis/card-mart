<?php
/* @var $this QuotationItemController */
/* @var $model QuotationItem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quotation-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>	

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>125)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
		
		<?php 
	$cs = Yii::app()->clientScript;
	$script =<<<EOD
	$("#itemName").live("blur", function(event) {
    var autocomplete = $(this).data("autocomplete");
    var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex($(this).val()) + "$", "i");
    var myInput = $(this);
    autocomplete.widget().children(".ui-menu-item").each(function() {
        //Check if each autocomplete item is a case-insensitive match on the input
        var item = $(this).data("item.autocomplete");
        if (matcher.test(item.label || item.value || item)) {
            //There was a match, lets stop checking
            autocomplete.selectedItem = item;
            return;
        }
    });
    //if there was a match trigger the select event on that match
    //I would recommend matching the label to the input in the select event
    if (autocomplete.selectedItem) {
        autocomplete._trigger("select", event, {
            item: autocomplete.selectedItem
        });
    //there was no match, clear the input
    } else {
        $(this).val('');
    }
}); 
	$('.noEnterSubmit').keypress(function(e){
    if ( e.which == 13 ){ 
    	e.preventDefault();
    	return false;
    }
});
EOD;
	$cs->registerScript('AddItemScript#form',$script);

?>