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

use Pingdom\Client;
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
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $checkRuntimeCache = [];

    /**
     * Initialize the current object
     */
    public function initializeObject()
    {
        $this->client = new Client($this->username, $this->password, $this->token);
    }

    /**
     * Get probes
     *
     * @return \Pingdom\Probe\Server[]
     */
    public function getProbes()
    {
        return $this->client->getProbes();
    }

    /**
     * @return array
     */
    public function getChecks()
    {
        if ($this->checkRuntimeCache !== []) {
            return $this->checkRuntimeCache;
        }
        foreach ($this->client->getChecks() as $check) {
            if (count($this->checks['filter']) > 0 && in_array($check['id'], $this->checks['filter']) === false) {
                continue;
            }
            $check['lasterrortime'] = \DateTime::createFromFormat('U', $check['lasterrortime']);
            $check['lasttesttime'] = \DateTime::createFromFormat('U', $check['lasttesttime']);
            $this->checkRuntimeCache[$check['id']] = $check;
        }
        return array_values($this->checkRuntimeCache);
    }

    /**
     * Return a list of raw test results for a specified check
     *
     * @param integer $checkId
     * @param integer $limit
     * @param array|null $probes
     * @return array
     * @throws Exception
     */
    public function getResults($checkId, $limit = 100, array $probes = null)
    {
        if (!$this->isCheckVisible($checkId)) {
            throw new Exception('Check not found', 1448563133);
        }
        return $this->client->getResults($checkId, $limit, $probes);
    }

    /**
     * Get Intervals of Average Response Time and Uptime During a Given Interval
     *
     * @param integer $checkId
     * @param string $resolution
     * @return array
     * @throws Exception
     */
    public function getPerformanceSummary($checkId, $resolution = 'hour')
    {
        if (!$this->isCheckVisible($checkId)) {
            throw new Exception('Check not found', 1448563133);
        }
        return $this->client->getPerformanceSummary($checkId, $resolution);
    }

    /**
     * @param integer $checkId
     * @return boolean
     */
    protected function isCheckVisible($checkId)
    {
        if (count($this->checks['filter']) === 0) {
            return true;
        }
        if ($this->checkRuntimeCache === []) {
            $this->getChecks();
        }
        return isset($this->checkRuntimeCache[$checkId]);
    }
}
