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
     * @var array
     */
    protected $checkRuntimeCache = [];

    /**
     * Initialize the current object
     */
    public function initializeObject()
    {
        $this->client = new PingdomApi($this->username, $this->password, $this->token);
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
            $check = $this->objectToArray($check);
            if (count($this->checks['filter']) > 0 && in_array($check['id'], $this->checks['filter']) === false) {
                continue;
            }
            $this->checkRuntimeCache[$check['id']] = $this->postprocessCheck($check);
        }
        return array_values($this->checkRuntimeCache);
    }

    /**
     * @param integer $checkIdentifier
     * @return array
     * @throws Exception
     */
    public function getCheck($checkIdentifier)
    {
        if (!$this->isCheckVisible($checkIdentifier)) {
            throw new Exception('Check not found', 1448563133);
        }
        $check = $this->objectToArray($this->client->getCheck($checkIdentifier));
        return $this->postprocessCheck($check);
    }

    /**
     * @param integer $checkIdentifier
     * @return array
     * @throws Exception
     */
    public function getAnalysis($checkIdentifier)
    {
        if (!$this->isCheckVisible($checkIdentifier)) {
            throw new Exception('Check not found', 1448563133);
        }
        return $this->client->getAnalysis($checkIdentifier);
    }

    /**
     * @param array $check
     * @return array
     */
    protected function postprocessCheck(array $check)
    {
        if (isset($check['lasterrortime'])) {
            $check['lasterrortime'] = \DateTime::createFromFormat('U', $check['lasterrortime']);
        }
        if (isset($check['lasttesttime'])) {
            $check['lasttesttime'] = \DateTime::createFromFormat('U', $check['lasttesttime']);
        }
        if (isset($check['created'])) {
            $check['created'] = \DateTime::createFromFormat('U', $check['created']);
        }
        return $check;
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

    /**
     * @param mixed $object
     * @return array
     */
    protected function objectToArray($object) {
        $json = json_encode($object);
        $array = json_decode($json, true);
        return $array;
    }
}
