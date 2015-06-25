<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Certificate extends \Entities\Certificate implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setSecretKey($secretKey)
    {
        $this->__load();
        return parent::setSecretKey($secretKey);
    }

    public function getSecretKey()
    {
        $this->__load();
        return parent::getSecretKey();
    }

    public function setPubicKey($pubicKey)
    {
        $this->__load();
        return parent::setPubicKey($pubicKey);
    }

    public function getPubicKey()
    {
        $this->__load();
        return parent::getPubicKey();
    }

    public function setValidFrom($validFrom)
    {
        $this->__load();
        return parent::setValidFrom($validFrom);
    }

    public function getValidFrom()
    {
        $this->__load();
        return parent::getValidFrom();
    }

    public function setValidTo($validTo)
    {
        $this->__load();
        return parent::setValidTo($validTo);
    }

    public function getValidTo()
    {
        $this->__load();
        return parent::getValidTo();
    }

    public function setCert($cert)
    {
        $this->__load();
        return parent::setCert($cert);
    }

    public function getCert()
    {
        $this->__load();
        return parent::getCert();
    }

    public function setUser(\Entities\User $user = NULL)
    {
        $this->__load();
        return parent::setUser($user);
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'secret_key', 'pubic_key', 'user');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}