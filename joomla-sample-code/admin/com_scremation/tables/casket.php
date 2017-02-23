<?php
// No direct access

defined('_JEXEC') or die;

class SCremationTableCasket extends JTable {

    public function __construct(&$db) {
        parent::__construct('#__scremation_caskets', 'id', $db);
    }

    public function bind($array, $ignore = '') {

		if(!JFactory::getUser()->authorise('core.edit.state','com_scremation') && $array['state'] == 1){
			$array['state'] = 0;
		}
		$array['price'] = str_replace(array('$', ','), '', $array['price']);

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }

        if(!JFactory::getUser()->authorise('core.admin', 'com_scremation.casket.'.$array['id'])){
            $actions = JFactory::getACL()->getActions('com_scremation','casket');
            $default_actions = JFactory::getACL()->getAssetRules('com_scremation.casket.'.$array['id'])->getData();
            $array_jaccess = array();
            foreach($actions as $action){
                $array_jaccess[$action->name] = $default_actions[$action->name];
            }
            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
        }

        //Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$this->setRules($array['rules']);
		}

		$params = JComponentHelper::getParams('com_scremation');
		
		$image_small_width = $params->get('smallWidth', 90);
		$image_small_height = $params->get('smallHeight', 90);
		$image_medium_width = $params->get('mediumWidth', 300);
		$image_medium_height = $params->get('mediumHeight', 300);
		$image_large_width = $params->get('largeWidth', 900);
		$image_large_height = $params->get('largeHeight', 900);
		$image_storiage_path = $params->get('imagePath', 'images/scremation/').'casket';
		
		$image_storiage_folder = JPATH_ROOT.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $image_storiage_path);
		
		$sub_folder = $image_storiage_folder;
		if(!file_exists($sub_folder)){
			mkdir($sub_folder, 0777, true);
		}

		//for adding new images of task save2copy...
		if(isset($_REQUEST["jform_image_s"]))
			$array['image_s'] = $_REQUEST["jform_image_s"];
		if(isset($_REQUEST["jform_image_m"]))
			$array['image_m'] = $_REQUEST["jform_image_m"];
		if(isset($_REQUEST["jform_image_l"]))
			$array['image_l'] = $_REQUEST["jform_image_l"];
		
		//image saving saving code from here...
		$cImage = isset($_FILES['casket_image']) ? $_FILES['casket_image'] : null;
		if(isset($cImage['error']) && $cImage['error'] == '0')
		{	
			$image_name = md5(microtime()).".jpg";;
			$image_small = 's_'.$image_name;
			$image_medium = 'm_'.$image_name;
			$image_large = 'l_'.$image_name;
			
			SCremationHelper::createImage($cImage['tmp_name'], $image_small,$image_medium, $image_large, $image_small_width, $image_small_height, $image_medium_width, $image_medium_height, $image_large_width, $image_large_height, '', $sub_folder, $cImage['name']);
			
			$array['o_image_s'] = $array['image_s'];
			$array['o_image_m'] = $array['image_m'];
			$array['o_image_l'] = $array['image_l'];

			$array['image_s'] = $image_storiage_path.'/'.$image_small;
			$array['image_m'] = $image_storiage_path.'/'.$image_medium;
			$array['image_l'] = $image_storiage_path.'/'.$image_large;
			
			if($array['o_image_s'] != $array['image_s'])
			{
				@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['o_image_s']);
				@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['o_image_m']);
				@unlink(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['o_image_l']);
			}
			
			unset($_FILES['casket_image']);
		}
		
		$task = JFactory::getApplication()->input->get('task');
		if($task == "save2copy" && !isset($_REQUEST['copytask']))
		{

			$image_name = md5(microtime()).".jpg";;
			$image_small = 's_'.$image_name;
			$image_medium = 'm_'.$image_name;
			$image_large = 'l_'.$image_name;
			
			copy(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['image_s'], $image_storiage_folder.DIRECTORY_SEPARATOR.$image_small); 
			copy(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['image_m'], $image_storiage_folder.DIRECTORY_SEPARATOR.$image_medium); 
			copy(JPATH_ROOT.DIRECTORY_SEPARATOR.$array['image_l'], $image_storiage_folder.DIRECTORY_SEPARATOR.$image_large);
			 
			$array['image_s'] = $image_storiage_path.'/'.$image_small;
			$array['image_m'] = $image_storiage_path.'/'.$image_medium;
			$array['image_l'] = $image_storiage_path.'/'.$image_large;
			
			$_REQUEST["jform_image_s"] = $array['image_s'];
			$_REQUEST["jform_image_m"] = $array['image_m'];
			$_REQUEST["jform_image_l"] = $array['image_l'];
			
			//for stop this process at once completed.
			$_REQUEST['copytask'] = true;
		}
		

        return parent::bind($array, $ignore);
    }

    /**
     * This function convert an array of JAccessRule objects into an rules array.
     * @param type $jaccessrules an arrao of JAccessRule objects.
     */

    private function JAccessRulestoArray($jaccessrules){
        $rules = array();
        foreach($jaccessrules as $action => $jaccess){
            $actions = array();
            foreach($jaccess->getData() as $group => $allow){
                $actions[$group] = ((bool)$allow);
            }
            $rules[$action] = $actions;
        }
        return $rules;
    }

    /**
     * Overloaded check function
     */

    public function check() {
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }
        return parent::check();
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table.  The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param    mixed    An optional array of primary key values to update.  If not
     *                    set the instance property value is used.
     * @param    integer The publishing state. eg. [0 = unpublished, 1 = published]
     * @param    integer The user id of the user performing the operation.
     * @return    boolean    True on success.
     * @since    1.0.4
     */

    public function publish($pks = null, $state = 1, $userId = 0) {
        // Initialise variables.
        $k = $this->_tbl_key;

		// Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';

        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE `' . $this->_tbl . '`' .
                ' SET `state` = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );

        $this->_db->query();

        // Check for a database error.
        if ($this->_db->getErrorNum()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }

    /**
      * Define a namespaced asset name for inclusion in the #__assets table
      * @return string The asset name 
      *
      * @see JTable::_getAssetName 
    */

    protected function _getAssetName() {
        $k = $this->_tbl_key;
        return 'com_scremation.casket.' . (int) $this->$k;
    }

    /**
      * Returns the parrent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
      *
      * @see JTable::_getAssetParentId 
    */

    protected function _getAssetParentId(JTable $table = NULL, $id = NULL){

        // We will retrieve the parent-asset from the Asset-table
        $assetParent = JTable::getInstance('Asset');
        // Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();
        // The item has the component as asset-parent
        $assetParent->loadByName('com_scremation');
        // Return the found asset-parent-id
        if ($assetParent->id){
            $assetParentId=$assetParent->id;
        }

        return $assetParentId;
    }

}

