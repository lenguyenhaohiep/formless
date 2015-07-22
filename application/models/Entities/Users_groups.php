<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Users_groups
 */
class Users_groups
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Entities\Groups
     */
    private $groups;

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
     * Set groups
     *
     * @param Entities\Groups $groups
     * @return Users_groups
     */
    public function setGroups(\Entities\Groups $groups = null)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * Get groups
     *
     * @return Entities\Groups 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set user
     *
     * @param Entities\User $user
     * @return Users_groups
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