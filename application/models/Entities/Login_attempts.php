<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Login_attempts
 */
class Login_attempts
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $ip_address
     */
    private $ip_address;

    /**
     * @var string $login
     */
    private $login;

    /**
     * @var integer $time
     */
    private $time;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip_address
     *
     * @param string $ipAddress
     * @return Login_attempts
     */
    public function setIpAddress($ipAddress)
    {
        $this->ip_address = $ipAddress;
        return $this;
    }

    /**
     * Get ip_address
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Login_attempts
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Login_attempts
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }
}