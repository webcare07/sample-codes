<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SCremationViewTributes extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		$input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');

		parent::display($tpl);
	}

}
