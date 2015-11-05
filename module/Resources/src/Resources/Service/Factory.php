<?php
/**
 * Factory for various top-level VuFind services.
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2014.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind2
 * @package  Service
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 */
namespace Resources\Service;
use Zend\ServiceManager\ServiceManager;

/**
 * Factory for various top-level VuFind services.
 *
 * @category VuFind2
 * @package  Service
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/vufind2:developer_manual Wiki
 *
 * @codeCoverageIgnore
 */
class Factory
{

    /**
     * Generic plugin manager factory (support method).
     *
     * @param ServiceManager $sm Service manager.
     * @param string         $ns VuFind namespace containing plugin manager
     *
     * @return object
     */
    public static function getGenericPluginManager(ServiceManager $sm, $ns)
    {
        $className = 'Resources\\' . $ns . '\PluginManager';
        $configKey = strtolower(str_replace('\\', '_', $ns));
        $config = $sm->get('Config');
        return new $className(
            new \Zend\ServiceManager\Config(
                $config['vufind']['plugin_managers'][$configKey]
            )
        );
    }

    /**
     * Construct the Content\Covers Plugin Manager.
     *
     * @param ServiceManager $sm Service manager.
     *
     * @return \VuFind\Content\Covers\PluginManager
     */
    public static function getContentCoversPluginManager(ServiceManager $sm)
    {
        return static::getGenericPluginManager($sm, 'Content\Covers');
    }

    /**
     * Construct the Session Manager.
     *
     * @param ServiceManager $sm Service manager.
     *
     * @return \Zend\Session\SessionManager
     */
    public static function getSessionManager(ServiceManager $sm)
    {
        $cookieManager = $sm->get('Resources\CookieManager');
        $sessionConfig = new \Zend\Session\Config\SessionConfig();
        $options = [
            'cookie_path' => $cookieManager->getPath(),
            'cookie_secure' => $cookieManager->isSecure()
        ];
        $domain = $cookieManager->getDomain();
        if (!empty($domain)) {
            $options['cookie_domain'] = $domain;
        }

        $sessionConfig->setOptions($options);

        return new \Zend\Session\SessionManager($sessionConfig);
    }

    /**
     * Construct the cookie manager.
     *
     * @param ServiceManager $sm Service manager.
     *
     * @return \Resources\Cookie\CookieManager
     */
    public static function getCookieManager(ServiceManager $sm)
    {
        $config = $sm->get('resourcesConfig')->get('config');
        $path = '/';
        if (isset($config->Cookies->limit_by_path)
            && $config->Cookies->limit_by_path
        ) {
            $path = $sm->get('Request')->getBasePath();
        }
        $secure = isset($config->Cookies->only_secure)
            ? $config->Cookies->only_secure
            : false;
        $domain = isset($config->Cookies->domain)
            ? $config->Cookies->domain
            : null;
        return new \Resources\Cookie\CookieManager($_COOKIE, $path, $domain, $secure);
    }

    /**
     * Construct the cache manager.
     *
     * @param ServiceManager $sm Service manager.
     *
     * @return \Resources\Cache\Manager
     */
    public static function getCacheManager(ServiceManager $sm)
    {

        $resourcesConfig = $sm->get('resourcesConfig');

        //at the moment I don't know what to do with the search cache
        //used in standard VuFind
        return new \Resources\Cache\Manager(
            $resourcesConfig,
            $resourcesConfig
        );

    }

    /**
     * Construct the HTTP service.
     *
     * @param ServiceManager $sm Service manager.
     *
     * @return \VuFindHttp\HttpService
     */
    public static function getHttp(ServiceManager $sm)
    {
        $config = $sm->get('resourcesConfig');
        //todo: wie gehen wir mit VuFind/Config um?
        //$config = $sm->get('VuFind\Config')->get('config');
        $options = [];
        if (isset($config->Proxy->host)) {
            $options['proxy_host'] = $config->Proxy->host;
            if (isset($config->Proxy->port)) {
                $options['proxy_port'] = $config->Proxy->port;
            }
            if (isset($config->Proxy->type)) {
                $options['proxy_type'] = $config->Proxy->type;
            }
        }
        $defaults = isset($config->Http)
            ? $config->Http->toArray() : [];
        return new \VuFindHttp\HttpService($options, $defaults);
    }





}
