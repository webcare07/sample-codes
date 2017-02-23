<?php
defined('_JEXEC') or die;

JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_scremation/tables');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/scremation.php';
require_once JPATH_COMPONENT.'/helpers/scremation.php';


// Execute the task.
$controller	= JControllerLegacy::getInstance('SCremation');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
