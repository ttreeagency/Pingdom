<?php
namespace Ttree\Neos\Pingdom\Controller\Module\Management;

/*
 * This file is part of the Ttree.Neos.Pingdom package.
 *
 * (c) Contributors of the project and the ttree team - www.ttree.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Ttree\Neos\Pingdom\Service\PingdomClient;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

class ReportController extends ActionController
{
    /**
     * @var PingdomClient
     * @Flow\Inject
     */
    protected $client;

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('checks', $this->client->getChecks());
    }

    /**
     * @param integer $identifier
     * @return void
     */
    public function showAction($identifier)
    {
        $check = $this->client->getCheck($identifier);
        $type = $check->getType();
        $tags = $check->getTags();
        $probeFilters = $check->getProbeFilters();
        $this->view->assignMultiple([
            'check' => $check,
            'type' => $type,
            'tags' => $tags,
            'probeFilters' => $probeFilters,
        ]);
    }

    /**
     * @param integer $identifier
     * @return void
     */
    public function pauseAction($identifier)
    {
        $this->client->pause($identifier);
        $this->addFlashMessage('Job has been paused with success');
        $this->redirect('index');
    }

    /**
     * @param integer $identifier
     * @return void
     */
    public function unpauseAction($identifier)
    {
        $this->client->unpause($identifier);
        $this->addFlashMessage('Job has been unpaused with success');
        $this->redirect('index');
    }
}
