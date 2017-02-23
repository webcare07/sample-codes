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
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= ( $listOrder == 'a.ordering' || $listOrder == 'ordering'  ) ? true: false;
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_scremation&task=caskets.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'casketsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$assoc		= isset($app->item_associations) ? $app->item_associations : 0;
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_scremation&view=caskets'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('Search'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('Submit'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('Clear'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('Limit'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<select name="filter_published" id="filter_published" class="input-medium" onchange="this.form.submit()">
                    <option value="">- Select Status -</option>
                    <option value="1" <?php if($this->state->get('filter.state') == '1') echo 'selected="selected"'; ?>>Published</option>
                    <option value="0" <?php if($this->state->get('filter.state') == '0') echo 'selected="selected"'; ?>>Unpublished</option>
                    <option value="2" <?php if($this->state->get('filter.state') == '2') echo 'selected="selected"'; ?>>Archived</option>
                    <option value="-2" <?php if($this->state->get('filter.state') == '-2') echo 'selected="selected"'; ?>>Trashed</option>
                    <option value="*" <?php if($this->state->get('filter.state') == '*') echo 'selected="selected"'; ?>>All</option>
				</select>
			</div>
        </div>
		<div class="clearfix"> </div>
		<p class="pagenote">
        	Manage Casket options for distributors.
        </p>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="casketsList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="40%">
						<?php echo JHtml::_('grid.sort', 'Title', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="30%">
						<?php echo JHtml::_('grid.sort', 'Price', 'a.price', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'ordering');
				$item->cat_link = JRoute::_('index.php?option=com_scremation&task=casket.edit&id='. $item->id);
				$canCreate  = $user->authorise('core.create',     'com_scremation.casket.' . $item->id);
				$canEdit    = $user->authorise('core.edit',       'com_scremation.casket.' . $item->id);
				$canChange  = $user->authorise('core.edit.state', 'com_scremation.casket.' . $item->id);
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1" item-id="<?php echo $item->id?>" level="1">
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="ordering[]" size="5"
							value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_scremation&task=casket.edit&id='.(int) $item->id); ?>">
									<?php echo $item->title; ?></a>
							<?php else : ?>
								<?php echo $item->title; ?>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'casket.');
								JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'caskets.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'caskets.');
								endif;

								JHtml::_('dropdown.divider');

								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'caskets.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'caskets.');
								endif;

								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'caskets.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'caskets.');
								endif;

								// render dropdown list
								echo JHtml::_('dropdown.render');
								?>
						</div>
					</td>
					<td class="">
						<?php echo '$'.$item->price; ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'caskets.', $canChange, 'cb'); ?>
					</td>
					<td class="center hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>