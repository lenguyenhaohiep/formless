<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Form_relation extends \Entities\Form_relation implements \Doctrine\ORM\Proxy\Proxy
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

    public function setAttr1($attr1)
    {
        $this->__load();
        return parent::setAttr1($attr1);
    }

    public function getAttr1()
    {
        $this->__load();
        return parent::getAttr1();
    }

    public function setAttr2($attr2)
    {
        $this->__load();
        return parent::setAttr2($attr2);
    }

    public function getAttr2()
    {
        $this->__load();
        return parent::getAttr2();
    }

    public function setForm1(\Entities\Form $form1 = NULL)
    {
        $this->__load();
        return parent::setForm1($form1);
    }

    public function getForm1()
    {
        $this->__load();
        return parent::getForm1();
    }

    public function setForm2(\Entities\Form $form2 = NULL)
    {
        $this->__load();
        return parent::setForm2($form2);
    }

    public function getForm2()
    {
        $this->__load();
        return parent::getForm2();
    }

    public function setType1(\Entities\Type $type1 = NULL)
    {
        $this->__load();
        return parent::setType1($type1);
    }

    public function getType1()
    {
        $this->__load();
        return parent::getType1();
    }

    public function setType2(\Entities\Type $type2 = NULL)
    {
        $this->__load();
        return parent::setType2($type2);
    }

    public function getType2()
    {
        $this->__load();
        return parent::getType2();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'attr1', 'attr2', 'type1', 'type2');
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