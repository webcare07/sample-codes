<?php
// No direct access
defined('_JEXEC') or die;

class SCremationController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{

		$view = JFactory::getApplication()->input->getCmd('view', 'dashboard');
        JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
