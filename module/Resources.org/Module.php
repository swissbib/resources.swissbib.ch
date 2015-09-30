<?php
namespace Resources;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface as Autoloadable;
use Zend\ModuleManager\Feature\ConfigProviderInterface as Configurable;



class Module implements Autoloadable, Configurable
{

	/**
	 * @return    Array|mixed|\Traversable
	 */
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}






	/**
	 * @return    Array
	 */
	public function getAutoloaderConfig()
	{

        //we want to use the classmap mechanism if we are not in development mode
        if (strcmp(APPLICATION_ENV,'development') != 0) {
            preg_match('/(.*?)module/',__DIR__,$matches);

            return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                    __NAMESPACE__ => __DIR__ . '/src/autoload_classmap.php',
                ),

                'Zend\Loader\StandardAutoloader' => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ),
                ),
            );


        } else {

            return array(
                'Zend\Loader\StandardAutoloader' => array(
                    'namespaces' => array(
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ),
                ),
            );

        }

	}



}
