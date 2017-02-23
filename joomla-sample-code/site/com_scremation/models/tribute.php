<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class SCremationModelTribute extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_SCREMATION';


    function __construct() {

        parent::__construct();
    }

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Tribute', $prefix = 'SCremationTable', $config = array())
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
		$form = $this->loadForm('com_scremation.tribute', 'tribute', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = null; //JFactory::getApplication()->getUserState('com_scremation.edit.tribute.data', array());

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
		$db = JFactory::getDBO();
		
		if ($item = parent::getItem($pk)) {
			//Do any procesing on fields here if needed
			$my = JFactory::getUser();
			if( $my->id )
			{
				$item->name = $my->name;
				$item->email = $my->email;
			}
			
			if( $item->arrangement_id )
			{
				$sql = "SELECT `distributor_id` FROM `#__scremation_corders` WHERE `id`=".$db->Quote($item->arrangement_id);
				$db->setQuery($sql);
				$distributor_id = $db->loadResult();
				if( $distributor_id )
					$item->ddata = SCremationHelper::getSCUser( $distributor_id );
			} 
		}

		return $item;
	}

}