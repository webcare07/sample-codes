<?php
// No direct access
defined('_JEXEC') or die;

class SCremationHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function getSubmenu($vName = '')
	{
		$submenus = array();
		$submenus[] = array('', '', '', 'divider');
		$submenus[] = array(JText::_('COM_SCREMATION_TITLE_DASHBOARD'),
			'index.php?option=com_scremation&view=dashboard',
			'dashboard', '');
			
		//prepare output...
		$output = '<ul class="nav nav-list">';
		foreach($submenus as $sub)
		{
			$output .= '<li class="'.$sub[3].' '.($sub[2]==$vName?'active':'').'">';
			if($sub[1] != '')
				$output .= '<a href="'.$sub[1].'">'.$sub[0].'</a>';
			else
				$output .= ''.$sub[0].'';
			$output .= '</li>';
		}
		$output .= '</ul></div>';
		
		return $output;
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_scremation';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}


}