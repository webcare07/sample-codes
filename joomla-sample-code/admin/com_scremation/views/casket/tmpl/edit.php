<?php

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_scremation/assets/css/scremation.css');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'casket.cancel' || document.formvalidator.isValid(document.id('casket-form'))) {
			Joomla.submitform(task, document.getElementById('casket-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_scremation&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="casket-form" class="form-validate">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset class="adminform">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_SCREMATION_LEGEND_CASKET', true)); ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('title'); ?>
					</div>
                </div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('price'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('price'); ?>
					</div>
                </div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('manufacture'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('manufacture'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('meterial'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('meterial'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('interior'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('interior'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('casket_image'); ?>
					</div>
					<div class="controls">
                    	<input type="text" value="" id="jform_casket_image_1" name="casket_image_1" class="" aria-invalid="false" style="float:left;" readonly="readonly" onclick="javascript: jQuery('#jform_casket_image').click();">
                        <input type="button" class="btn btn-danger" value="Browse" onclick="javascript: jQuery('#jform_casket_image').click();" style="float:left; margin-left:5px; " />
                    	<input type="file" value="" id="jform_casket_image" name="casket_image" class="" aria-invalid="false" style="float:left; display:none;" onchange="javascript:jQuery('#jform_casket_image_1').val(jQuery(this).val());">
                        <?php if(!empty($this->item->image_s)): ?>
                        	<img src="<?php echo JURI::root().$this->item->image_s ?>" style="float:left; margin-left:10px; border:3px solid #030;" />
                        <?php endif; ?>
					</div>
				</div>
				<div class="control-group" style="clear:both;">
					<div class="control-label">
						<?php echo $this->form->getLabel('state'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('state'); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>