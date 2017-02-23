<?php
/**
 * @version     1.0.0
 * @package     com_scremation
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_scremation/assets/css/scremation.css');

$app	= JFactory::getApplication();
$user	= JFactory::getUser();

$userId	= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_scremation');
?>
<style>
.icon-ok.yellow:before { color: #FF9900 !important; }
</style>
<div id="scremation">
<form action="<?php echo JRoute::_('index.php?option=com_scremation&view=tributes'); ?>" method="post" name="adminForm" id="adminForm">
		<h1>Recent Passings</h1>
		<p class="pagenotes">
        	To read the Obituary, obtain Service Details, sign the Guest Book and much more, please click on the name of the deceased below or search by Name or Next of Kin.
        </p>
        <div class="filter-search" style="text-align: center; margin: 10px;">
            <input type="text" name="filter_search" placeholder="<?php echo JText::_('Enter Name of Deceased or Next of Kin'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" style="padding: 10px; width: 250px; margin: 0px 10px;" />
            <input type="submit" class="btn btn-primary tip hasTooltip" title="<?php echo JText::_('Search'); ?>" value="<?php echo JText::_('Search'); ?>" style="font-size: 16px; padding: 9px 30px;"/>
        </div>
		<div class="clearfix"> </div>
        <div class="sctable" style="margin: 0px auto; width: 80%;">
		<table id="tributesList" width="100%">
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="center">
                       <h3 style="color:#B9431F;">Obituary and Tribute Details</h3>
			<p class="pagenotes">Preview page by clicking the link</p>
                        <hr />
					</td>
                </tr>
			<?php foreach ($this->items as $i => $item) :
				?>
				<tr>
					<td class="center">
                       <h3 class="dname"><a href="<?php echo JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$item->id); ?>"><?php echo $item->fullname; ?></a></h3>
						<h3><?php echo date("F j, Y", strtotime($item->dod)); ?></h3>
                        <hr />
					</td>
                </tr>
				<?php endforeach; ?>
			</tbody>
		</table>
        </div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

</form>
</div>
<div id="scremation-right">
	<div style="padding:10px; text-align:center; display:none;">
        <img src="<?php echo JURI::root();?>components/com_scremation/assets/images/logo.png" />
        <br /><br />
        <input type="button" value=" Read Our Testmonials " class="btn btn-primary" />
    </div>
</div>
<div class="clear"></div>
<style>
#tributesList .dname { font-size:25px; font-weight:bold; margin-top:0px; }
#tributesList hr { border-top: 2px solid #DBDBDB; margin: 20px 0 0; }
</style>
