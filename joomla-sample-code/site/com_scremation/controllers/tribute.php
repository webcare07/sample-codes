<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class SCremationControllerTribute extends JControllerForm
{

    function __construct() {
        $this->view_list = '';

        parent::__construct();
    }

	function getsecuritycode()
	{
		$name = JRequest::getVar('name', '');
		SCremationHelperSite::getSecurityCode($name);
	}

	function postguestbook()
	{
		$data = $this->input->post->get('jform', array(), 'array');
		$session = JFactory::getSession();

		$model = $this->getModel('Tribute');
		$table = $model->getTable('Tribute_Guestbook');

		if(isset($data['tribute_id']) && $data['tribute_id'])
		{
			if($data['code'] == $session->get('guestbookcode'))
			{
				$data['created'] = date('Y-m-d H:i:s', time());
				$table->bind($data);
				$table->store();
				$this->setMessage(JText::_('Your message has been posted on Guest Book.'), 'message');
				$session->set('guestbookcode', '');

				$tribute = $model->getTable();
				$tribute->load($data['tribute_id']);
				if( $tribute->customer_id )
				{
					$customer = SCremationHelper::getSCUser($tribute->customer_id);
					SCMailHelper::sendMailOnCustomerGuestBookSignNotify($data, $customer);
				}
			}
			else
				$this->setMessage(JText::_('Wrong security Code.'), 'error');
			
			$this->setRedirect(JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$data['tribute_id'], false));

		}
		else			
		{
			$this->setMessage(JText::_('Error on Post Message.'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$data['tribute_id'], false));
		}
				
	}

	function sendpersonalnote()
	{
		$data = $this->input->post->get('jform', array(), 'array');
		$session = JFactory::getSession();

		$model = $this->getModel('Tribute');
		$tribute = $model->getTable();

		if(isset($data['tribute_id']) && $data['tribute_id'])
		{
			if($data['code'] == $session->get('sendpersonalcode'))
			{
				$tribute->load($data['tribute_id']);
				if( $tribute->customer_id )
				{
					$customer = SCremationHelper::getSCUser($tribute->customer_id);
					SCMailHelper::sendMailOnGuestPersonalCondolenceNotify($data, $customer);
					$this->setMessage(JText::_('Your message has been sent to the family.'), 'message');
				}
				else
					$this->setMessage(JText::_('Error on Send Message.'), 'error');
					
				$session->set('sendpersonalcode', '');
			}
			else
				$this->setMessage(JText::_('Wrong security Code.'), 'error');
			
			$this->setRedirect(JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$data['tribute_id'], false));

		}
		else			
		{
			$this->setMessage(JText::_('Error on Send Message.'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$data['tribute_id'], false));
		}
				
	}

}
