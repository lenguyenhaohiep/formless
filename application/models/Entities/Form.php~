<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Form
 */
class Form
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $path_form
     */
    private $path_form;

    /**
     * @var datetime $created_date
     */
    private $created_date;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var Entities\User
     */
    private $user;

    /**
     * @var Entities\Type
     */
    private $type;


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
     * Set title
     *
     * @param string $title
     * @return Form
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set path_form
     *
     * @param string $pathForm
     * @return Form
     */
    public function setPathForm($pathForm)
    {
        $this->path_form = $pathForm;
        return $this;
    }

    /**
     * Get path_form
     *
     * @return string 
     */
    public function getPathForm()
    {
        return $this->path_form;
    }

    /**
     * Set created_date
     *
     * @param datetime $createdDate
     * @return Form
     */
    public function setCreatedDate($createdDate)
    {
        $this->created_date = $createdDate;
        return $this;
    }

    /**
     * Get created_date
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Form
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param Entities\User $user
     * @return Form
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

    /**
     * Set type
     *
     * @param Entities\Type $type
     * @return Form
     */
    public function setType(\Entities\Type $type = null)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return Entities\Type 
     */
    public function getType()
    {
        return $this->type;
    }
}