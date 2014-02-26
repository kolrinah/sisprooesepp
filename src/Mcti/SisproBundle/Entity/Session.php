<?php

namespace Mcti\SisproBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
class Session
{
    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $sessionValue;

    /**
     * @var integer
     */
    private $sessionTime;


    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set sessionValue
     *
     * @param string $sessionValue
     * @return Session
     */
    public function setSessionValue($sessionValue)
    {
        $this->sessionValue = $sessionValue;
    
        return $this;
    }

    /**
     * Get sessionValue
     *
     * @return string 
     */
    public function getSessionValue()
    {
        return $this->sessionValue;
    }

    /**
     * Set sessionTime
     *
     * @param integer $sessionTime
     * @return Session
     */
    public function setSessionTime($sessionTime)
    {
        $this->sessionTime = $sessionTime;
    
        return $this;
    }

    /**
     * Get sessionTime
     *
     * @return integer 
     */
    public function getSessionTime()
    {
        return $this->sessionTime;
    }
}