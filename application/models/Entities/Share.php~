<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Share
 */
class Share
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $attrs
     */
    private $attrs;

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
     * Set attrs
     *
     * @param string $attrs
     * @return Share
     */
    public function setAttrs($attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }

    /**
     * Get attrs
     *
     * @return string 
     */
    public function getAttrs()
    {
        return $this->attrs;
    }

    /**
     * Set form
     *
     * @param Entities\Form $form
     * @return Share
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
     * @return Share
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