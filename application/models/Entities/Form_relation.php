<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Form_relation
 */
class Form_relation
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $attr1
     */
    private $attr1;

    /**
     * @var string $attr2
     */
    private $attr2;

    /**
     * @var Entities\Form
     */
    private $form1;

    /**
     * @var Entities\Form
     */
    private $form2;


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
     * Set attr1
     *
     * @param string $attr1
     * @return Form_relation
     */
    public function setAttr1($attr1)
    {
        $this->attr1 = $attr1;
        return $this;
    }

    /**
     * Get attr1
     *
     * @return string 
     */
    public function getAttr1()
    {
        return $this->attr1;
    }

    /**
     * Set attr2
     *
     * @param string $attr2
     * @return Form_relation
     */
    public function setAttr2($attr2)
    {
        $this->attr2 = $attr2;
        return $this;
    }

    /**
     * Get attr2
     *
     * @return string 
     */
    public function getAttr2()
    {
        return $this->attr2;
    }

    /**
     * Set form1
     *
     * @param Entities\Form $form1
     * @return Form_relation
     */
    public function setForm1(\Entities\Form $form1 = null)
    {
        $this->form1 = $form1;
        return $this;
    }

    /**
     * Get form1
     *
     * @return Entities\Form 
     */
    public function getForm1()
    {
        return $this->form1;
    }

    /**
     * Set form2
     *
     * @param Entities\Form $form2
     * @return Form_relation
     */
    public function setForm2(\Entities\Form $form2 = null)
    {
        $this->form2 = $form2;
        return $this;
    }

    /**
     * Get form2
     *
     * @return Entities\Form 
     */
    public function getForm2()
    {
        return $this->form2;
    }
    /**
     * @var Entities\Type
     */
    private $type1;

    /**
     * @var Entities\Type
     */
    private $type2;


    /**
     * Set type1
     *
     * @param Entities\Type $type1
     * @return Form_relation
     */
    public function setType1(\Entities\Type $type1 = null)
    {
        $this->type1 = $type1;
        return $this;
    }

    /**
     * Get type1
     *
     * @return Entities\Type 
     */
    public function getType1()
    {
        return $this->type1;
    }

    /**
     * Set type2
     *
     * @param Entities\Type $type2
     * @return Form_relation
     */
    public function setType2(\Entities\Type $type2 = null)
    {
        $this->type2 = $type2;
        return $this;
    }

    /**
     * Get type2
     *
     * @return Entities\Type 
     */
    public function getType2()
    {
        return $this->type2;
    }
}