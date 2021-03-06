<?php
/**
 * Cover Controller
 *
 * PHP Version 5
 *
 * Copyright (C) Villanova University 2011.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA    02111-1307    USA
 *
 * @category VuFind2
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.vufind.org  Main Page
 */
namespace Resources\Controller;
use Resources\Cover\Loader;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Generates covers for book entries
 *
 * @category VuFind2
 * @package  Controller
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @author  Guenter Hipler <guenter.hipler@unibas.ch>
 * @author Matthias Edel    <matthias.edel@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://www.vufind.org  Main Page
 */
class CoverController extends AbstractActionController
{
    /**
     * Cover loader
     *
     * @var Loader
     */
    protected $loader = false;

    /**
     * Get the cover loader object
     *
     * @return Loader
     */
    protected function getLoader()
    {
        // Construct object for loading cover images if it does not already exist:
        if (!$this->loader) {
            //wie gehen wir mit dem cache Manager um??
            $cacheDir = $this->getServiceLocator()->get('Resources\CacheManager')
                ->getCache('cover')->getOptions()->getCacheDir();

            //I think we don't need the ThemeInfo Type for our purposes
            $this->loader = new Loader(
                $this->getConfig(),
                $this->getServiceLocator()->get('Resources\ContentCoversPluginManager'),
                //$this->getServiceLocator()->get('VuFindTheme\ThemeInfo'),
                $this->getServiceLocator()->get('VuFind\Http')->createClient(),
                $cacheDir
            );
            //Initializers are not necessary - an assumption
            //\VuFind\ServiceManager\Initializer::initInstance(
            //    $this->loader, $this->getServiceLocator()
            //);
        }
        return $this->loader;
    }

    /**
     * Send image data for display in the view
     *
     * @return \Zend\Http\Response
     */
    public function showAction()
    {
        $this->writeSession();  // avoid session write timing bug

        // Special case: proxy a full URL:
        $proxy = $this->params()->fromQuery('proxy');
        if (!empty($proxy)) {
            return $this->proxyUrl($proxy);
        }

        // Default case -- use image loader:
        $this->getLoader()->loadImage(
            // Legacy support for "isn" param which has been superseded by isbn:
            $this->params()->fromQuery('isbn', $this->params()->fromQuery('isn')),
            $this->params()->fromQuery('size'),
            $this->params()->fromQuery('contenttype'),
            $this->params()->fromQuery('title'),
            $this->params()->fromQuery('author'),
            $this->params()->fromQuery('callnumber'),
            $this->params()->fromQuery('issn'),
            $this->params()->fromQuery('oclc'),
            $this->params()->fromQuery('upc')
        );
        return $this->displayImage();
    }

    /**
     * Return the default 'image not found' information
     *
     * @return \Zend\Http\Response
     */
    public function unavailableAction()
    {
        $this->writeSession();  // avoid session write timing bug
        $this->getLoader()->loadUnavailable();
        return $this->displayImage();
    }

    /**
     * Support method -- update the view to display the image currently found in the
     * \VuFind\Cover\Loader.
     *
     * @return \Zend\Http\Response
     */
    protected function displayImage()
    {
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine(
            'Content-type', $this->getLoader()->getContentType()
        );

        // Send proper caching headers so that the user's browser
        // is able to cache the cover images and not have to re-request
        // then on each page load. Default TTL set at 14 days

        $coverImageTtl = (60 * 60 * 24 * 14); // 14 days
        $headers->addHeaderLine(
            'Cache-Control', "maxage=" . $coverImageTtl
        );
        $headers->addHeaderLine(
            'Pragma', 'public'
        );
        $headers->addHeaderLine(
            'Expires', gmdate('D, d M Y H:i:s', time() + $coverImageTtl) . ' GMT'
        );

        $response->setContent($this->getLoader()->getImage());
        return $response;
    }

    /**
     * Proxy a URL.
     *
     * @param string $url URL to proxy
     *
     * @return \Zend\Http\Response
     */
    protected function proxyUrl($url)
    {
        $client = $this->getServiceLocator()->get('VuFind\Http')->createClient();
        return $client->setUri($url)->send();
    }

    /**
     * Get a VuFind configuration.
     * Copied from AbstractBase part of VuFind Controller namespace
     * @param string $id Configuration identifier (default = main VuFind config)
     *
     * @return \Zend\Config\Config
     */
    public function getConfig($id = 'config')
    {
        return $this->getServiceLocator()->get('Resources\Config')->get($id);
    }


    /**
     * Write the session -- this is designed to be called prior to time-consuming
     * AJAX operations.  This should help reduce the odds of a timing-related bug
     * that causes the wrong version of session data to be written to disk (see
     * VUFIND-716 for more details).
     *
     * @return void
     */
    protected function writeSession()
    {
        $this->getServiceLocator()->get('Resources\SessionManager')->writeClose();
    }


}

