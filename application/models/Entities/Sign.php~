<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Sign
 */
class Sign
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $data
     */
    private $data;

    /**
     * @var Entities\Form
     */
    private $form;

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
     * Set data
     *
     * @param string $data
     * @return Sign
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set form
     *
     * @param Entities\Form $form
     * @return Sign
     */
    public function setForm(\Entities\Form $form = null)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get form
     *
     * @return Entities\Form 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set user
     *
     * @param Entities\User $user
     * @return Sign
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