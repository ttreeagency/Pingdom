<?php
namespace Ttree\Neos\Pingdom\Service;

/*
 * This file is part of the Ttree.Neos.Pingdom package.
 *
 * (c) Contributors of the project and the ttree team - www.ttree.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Acquia\Pingdom\PingdomApi;
use Ttree\Neos\Pingdom\Controller\Module\Domain\Model\Check;
use Ttree\Neos\Pingdom\Controller\Module\Domain\Model\Checks;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;

/**
 * Pingdom API Client
 *
 * @Flow\Scope("singleton")
 * @api
 */
class PingdomClient
{

    /**
     * @var string
     * @Flow\InjectConfiguration("username")
     */
    protected $username;

    /**
     * @var string
     * @Flow\InjectConfiguration("password")
     */
    protected $password;

    /**
     * @var string
     * @Flow\InjectConfiguration("token")
     */
    protected $token;

    /**
     * @var string
     * @Flow\InjectConfiguration("checks")
     */
    protected $checks;

    /**
     * @var PingdomApi
     */
    protected $client;

    /**
     * @var Checks
     */
    protected $checksRuntimeCache;

    /**
     * Initialize the current object
     */
    public function initializeObject()
    {
        $this->client = new PingdomApi($this->username, $this->password, $this->token);
    }

    /**
     * @return Checks
     */
    public function getChecks()
    {
        if ($this->checksRuntimeCache !== null) {
            return $this->checksRuntimeCache;
        }
        $this->checksRuntimeCache = new Checks();
        foreach ($this->client->getChecks() as $check) {
            if (count($this->checks['filter']) > 0 && in_array($check->id, $this->checks['filter']) === false) {
                continue;
            }
            $check = new Check($check);
            $this->checksRuntimeCache->attach($check);
        }
        return $this->checksRuntimeCache;
    }

    /**
     * @param integer $checkIdentifier
     * @return Check
     * @throws Exception
     */
    public function getCheck($checkIdentifier)
    {
        $this->isCheckVisible($checkIdentifier);
        return new Check($this->client->getCheck($checkIdentifier));
    }

    /**
     * @param integer $checkIdentifier
     * @return array
     * @throws Exception
     */
    public function getAnalysis($checkIdentifier)
    {
        $this->isCheckVisible($checkIdentifier);
        return $this->client->getAnalysis($checkIdentifier);
    }

    /**
     * @param integer $checkIdentifier
     * @return array
     * @throws Exception
     */
    public function pause($checkIdentifier)
    {
        $this->isCheckVisible($checkIdentifier);
        return $this->client->pauseCheck($checkIdentifier);
    }

    /**
     * @param integer $checkIdentifier
     * @return array
     * @throws Exception
     */
    public function unpause($checkIdentifier)
    {
        $this->isCheckVisible($checkIdentifier);
        return $this->client->unpauseCheck($checkIdentifier);
    }

    /**
     * @param integer $checkId
     * @return bool
     * @throws Exception
     */
    protected function isCheckVisible($checkId)
    {
        if (count($this->checks['filter']) === 0) {
            return true;
        }
        if ($this->checksRuntimeCache === null) {
            $this->getChecks();
        }
        if (!isset($this->checksRuntimeCache[$checkId])) {
            throw new Exception('Check not found', 1448563133);
        }
        return true;
    }
}
