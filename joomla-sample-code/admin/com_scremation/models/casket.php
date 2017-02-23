<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class SCremationModelCasket extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_SCREMATION';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Casket', $prefix = 'SCremationTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_scremation.casket', 'casket', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_scremation.edit.casket.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			$item->price = str_replace(array('$', ','), '', $item->price);
			$item->price = '$'.($item->price?$item->price:'0.00');

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {
			$date = JFactory::getDate();
			// Set the values
			$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__scremation_caskets');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

	
	function delete(&$pks)
	{
		$cids	= JRequest::getVar('cid', array(), '', 'array');
		if(count($cids))
		foreach($cids as $id)
		{
			$row  =& $this->getTable();
			$row->load($id);
			$row->image_s = str_replace('/', DIRECTORY_SEPARATOR, $row->image_s);
			$row->image_m = str_replace('/', DIRECTORY_SEPARATOR, $row->image_m);
			$row->image_l = str_replace('/', DIRECTORY_SEPARATOR, $row->image_l);
			@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$row->image_s);
			@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$row->image_m);
			@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$row->image_l);
			$row->delete();
		}
	}

}