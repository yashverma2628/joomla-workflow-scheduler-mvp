<?php
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;

class PlgWorkflowAutomation extends CMSPlugin
{
    /**
     * CHANGED TO: onWorkflowBeforeTransition
     * This fires BEFORE the database updates the article status.
     */
    public function onWorkflowBeforeTransition($context, $pks, $transitionId, $workflowId)
    {
        // 1. Connect to Database to find our delay
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('auto_delay'))
            ->from($db->quoteName('#__workflow_transitions'))
            ->where($db->quoteName('id') . ' = ' . (int) $transitionId);
        
        $db->setQuery($query);
        $delay = (int) $db->loadResult();

        // 2. The Logic
        if ($delay > 0) {
            // The article is still "Unpublished" at this exact moment.
            // We wait here, THEN Joomla proceeds to publish it.
            sleep($delay);
            
            Factory::getApplication()->enqueueMessage('Automated delay of ' . $delay . ' seconds completed. Now publishing...', 'info');
        }
    }
}