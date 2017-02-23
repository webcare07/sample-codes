<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/scremation.php';


if (!JFactory::getUser()->authorise('core.manage', 'com_scremation'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JTable::addIncludePath(JPATH_COMPONENT.'/tables'); 

// Execute the task.
$controller	= JControllerLegacy::getInstance('SCremation');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
