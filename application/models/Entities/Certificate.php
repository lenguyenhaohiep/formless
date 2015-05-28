<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Certificate
 */
class Certificate
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $secret_key
     */
    private $secret_key;

    /**
     * @var string $pubic_key
     */
    private $pubic_key;

    /**
     * @var datetime $valid_from
     */
    private $valid_from;

    /**
     * @var datetime $valid_to
     */
    private $valid_to;

    /**
     * @var string $cert
     */
    private $cert;

    /**
     * @var Entities\User
     */
    private $user;


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
     * Set secret_key
     *
     * @param string $secretKey
     * @return Certificate
     */
    public function setSecretKey($secretKey)
    {
        $this->secret_key = $secretKey;
        return $this;
    }

    /**
     * Get secret_key
     *
     * @return string 
     */
    public function getSecretKey()
    {
        return $this->secret_key;
    }

    /**
     * Set pubic_key
     *
     * @param string $pubicKey
     * @return Certificate
     */
    public function setPubicKey($pubicKey)
    {
        $this->pubic_key = $pubicKey;
        return $this;
    }

    /**
     * Get pubic_key
     *
     * @return string 
     */
    public function getPubicKey()
    {
        return $this->pubic_key;
    }

    /**
     * Set valid_from
     *
     * @param datetime $validFrom
     * @return Certificate
     */
    public function setValidFrom($validFrom)
    {
        $this->valid_from = $validFrom;
        return $this;
    }

    /**
     * Get valid_from
     *
     * @return datetime 
     */
    public function getValidFrom()
    {
        return $this->valid_from;
    }

    /**
     * Set valid_to
     *
     * @param datetime $validTo
     * @return Certificate
     */
    public function setValidTo($validTo)
    {
        $this->valid_to = $validTo;
        return $this;
    }

    /**
     * Get valid_to
     *
     * @return datetime 
     */
    public function getValidTo()
    {
        return $this->valid_to;
    }

    /**
     * Set cert
     *
     * @param string $cert
     * @return Certificate
     */
    public function setCert($cert)
    {
        $this->cert = $cert;
        return $this;
    }

    /**
     * Get cert
     *
     * @return string 
     */
    public function getCert()
    {
        return $this->cert;
    }

    /**
     * Set user
     *
     * @param Entities\User $user
     * @return Certificate
     */
    public function setUser(\Entities\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Entities\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}