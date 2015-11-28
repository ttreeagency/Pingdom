<?php
namespace Ttree\Neos\Pingdom\Controller\Module\Domain\Model;

/*
 * This file is part of the Ttree.Neos.Pingdom package.
 *
 * (c) Contributors of the project and the ttree team - www.ttree.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use MyProject\Proxies\__CG__\stdClass;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Arrays;

class Check
{
    const STATUS_UP = 'up';
    const STATUS_PAUSED = 'paused';

    /**
     * @var array
     */
    protected $check = [];

    /**
     * @param array|stdClass $check
     */
    public function __construct($check)
    {
        $check = json_encode($check);
        $check = json_decode($check, true);
        if (isset($check['lasterrortime'])) {
            $check['lasterrortime'] = \DateTime::createFromFormat('U', $check['lasterrortime']);
        }
        if (isset($check['lasttesttime'])) {
            $check['lasttesttime'] = \DateTime::createFromFormat('U', $check['lasttesttime']);
        }
        if (isset($check['created'])) {
            $check['created'] = \DateTime::createFromFormat('U', $check['created']);
        }

        $this->check = $check;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return Arrays::getValueByPath($this->check, 'id');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return Arrays::getValueByPath($this->check, 'status');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Arrays::getValueByPath($this->check, 'name');
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return Arrays::getValueByPath($this->check, 'hostname');
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return Arrays::getValueByPath($this->check, 'created');
    }

    /**
     * @return \DateTime
     */
    public function getLastErrorTime()
    {
        return Arrays::getValueByPath($this->check, 'lasterrortime');
    }

    /**
     * @return \DateTime
     */
    public function getLastTestTime()
    {
        return Arrays::getValueByPath($this->check, 'lasttesttime');
    }

    /**
     * @return integer
     */
    public function getLastResponseTime()
    {
        return Arrays::getValueByPath($this->check, 'lastresponsetime');
    }

    /**
     * @return integer
     */
    public function getResolution()
    {
        return Arrays::getValueByPath($this->check, 'resolution');
    }

    /**
     * @return boolean
     */
    public function getSendToEmail()
    {
        return Arrays::getValueByPath($this->check, 'sendtoemail');
    }

    /**
     * @return boolean
     */
    public function getSendToSMS()
    {
        return Arrays::getValueByPath($this->check, 'sendtosms');
    }

    /**
     * @return boolean
     */
    public function getSendToTwitter()
    {
        return Arrays::getValueByPath($this->check, 'sendtotwitter');
    }

    /**
     * @return boolean
     */
    public function getSendToIphone()
    {
        return Arrays::getValueByPath($this->check, 'sendtoiphone');
    }

    /**
     * @return boolean
     */
    public function getSendToAndroid()
    {
        return Arrays::getValueByPath($this->check, 'sendtoandroid');
    }

    /**
     * @return integer
     */
    public function getSendNotificationWhenDown()
    {
        return Arrays::getValueByPath($this->check, 'sendnotificationwhendown');
    }

    /**
     * @return integer
     */
    public function getNotifyAgainEvery()
    {
        return Arrays::getValueByPath($this->check, 'notifyagainevery');
    }

    /**
     * @return boolean
     */
    public function getNotifyWhenBackup()
    {
        return Arrays::getValueByPath($this->check, 'notifywhenbackup');
    }

    /**
     * @return boolean
     */
    public function getUseLegacyNotifications()
    {
        return Arrays::getValueByPath($this->check, 'use_legacy_notifications');
    }

    /**
     * @return array
     */
    public function getType()
    {
        return Arrays::getValueByPath($this->check, 'type') ?: [];
    }

    /**
     * @return array
     */
    public function getContactIds()
    {
        return Arrays::getValueByPath($this->check, 'contactids') ?: [];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return Arrays::getValueByPath($this->check, 'tags') ?: [];
    }

    /**
     * @return array
     */
    public function getProbeFilters()
    {
        return Arrays::getValueByPath($this->check, 'probe_filters') ?: [];
    }

    /**
     * @return boolean
     */
    public function getIsPaused()
    {
        return $this->check['status'] === self::STATUS_PAUSED ? true : false;
    }

    /**
     * @return boolean
     */
    public function getIPV6()
    {
        return $this->check['ipv6'] === self::STATUS_PAUSED ? true : false;
    }
}
