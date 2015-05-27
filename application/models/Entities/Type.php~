<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Type
 */
class Type
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
     * @var string $path_template
     */
    private $path_template;

    /**
     * @var Entities\Group_type
     */
    private $group_type;


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
     * @return Type
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
     * Set path_template
     *
     * @param string $pathTemplate
     * @return Type
     */
    public function setPathTemplate($pathTemplate)
    {
        $this->path_template = $pathTemplate;
        return $this;
    }

    /**
     * Get path_template
     *
     * @return string 
     */
    public function getPathTemplate()
    {
        return $this->path_template;
    }

    /**
     * Set group_type
     *
     * @param Entities\Group_type $groupType
     * @return Type
     */
    public function setGroupType(\Entities\Group_type $groupType = null)
    {
        $this->group_type = $groupType;
        return $this;
    }

    /**
     * Get group_type
     *
     * @return Entities\Group_type 
     */
    public function getGroupType()
    {
        return $this->group_type;
    }
}