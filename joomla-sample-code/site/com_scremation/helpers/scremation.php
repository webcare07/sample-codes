<?php
// No direct access
defined('_JEXEC') or die;

class SCremationHelperSite
{
	public static function getPendingArrangementsCount($customer_ids = array())
	{
		$options = 0;
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
			->select(' COUNT(*) ')
			->from('#__scremation_corders AS a');

		$query->where('a.order_status=0 AND a.customer_id IN ('.@implode(',', $customer_ids).')');
		
		$db->setQuery($query);

		try
		{
			$options = (int)$db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getCompleteArrangementsCount($customer_ids = array())
	{
		$options = 0;
		$db = JFactory::getDbo();
	
		$query = $db->getQuery(true)
			->select(' COUNT(*) ')
			->from('#__scremation_corders AS a');

		$query->where('a.order_status=1 AND a.customer_id IN ('.@implode(',', $customer_ids).')');
		
		$db->setQuery($query);

		try
		{
			$options = (int)$db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getFutureArrangementsCount($customer_ids = array())
	{
		$options = 0;
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
			->select(' COUNT(*) ')
			->from('#__scremation_corders AS a');

		$query->where('a.order_type=2 AND a.order_status=0 AND a.customer_id IN ('.@implode(',', $customer_ids).')');
		
		$db->setQuery($query);

		try
		{
			$options = (int)$db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}
}