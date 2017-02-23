<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class SCremationControllerCasket extends JControllerForm
{

    function __construct() {
        $this->view_list = 'caskets';
        parent::__construct();
    }

}