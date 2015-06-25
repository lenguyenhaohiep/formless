<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Modify_history
 */
class Modify_history
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var datetime $modified_date
     */
    private $modified_date;

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
     * Set modified_date
     *
     * @param datetime $modifiedDate
     * @return Modify_history
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modified_date = $modifiedDate;
        return $this;
    }

    /**
     * Get modified_date
     *
     * @return datetime 
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * Set form
     *
     * @param Entities\Form $form
     * @return Modify_history
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
     * @return Modify_history
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