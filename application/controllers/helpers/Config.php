<?php
class Application_Controller_Action_Helper_Config extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @return array
     */
    public function direct()
    {
        //permet de récupérer les paramètres de l'application dans le fichier application.ini (pauseter.*)
        $bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
        return $bootstrap->getOption('pauseter');
    }
}
?>