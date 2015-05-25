<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Send_history
 */
class Send_history
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var datetime $send_date
     */
    private $send_date;

    /**
     * @var text $message
     */
    private $message;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var Entities\Form
     */
    private $form;

    /**
     * @var Entities\User
     */
    private $from_user;

    /**
     * @var Entities\User
     */
    private $to_user;


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
     * Set send_date
     *
     * @param datetime $sendDate
     * @return Send_history
     */
    public function setSendDate($sendDate)
    {
        $this->send_date = $sendDate;
        return $this;
    }

    /**
     * Get send_date
     *
     * @return datetime 
     */
    public function getSendDate()
    {
        return $this->send_date;
    }

    /**
     * Set message
     *
     * @param text $message
     * @return Send_history
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return text 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Send_history
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
     * Set form
     *
     * @param Entities\Form $form
     * @return Send_history
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
     * Set from_user
     *
     * @param Entities\User $fromUser
     * @return Send_history
     */
    public function setFromUser(\Entities\User $fromUser = null)
    {
        $this->from_user = $fromUser;
        return $this;
    }

    /**
     * Get from_user
     *
     * @return Entities\User 
     */
    public function getFromUser()
    {
        return $this->from_user;
    }

    /**
     * Set to_user
     *
     * @param Entities\User $toUser
     * @return Send_history
     */
    public function setToUser(\Entities\User $toUser = null)
    {
        $this->to_user = $toUser;
        return $this;
    }

    /**
     * Get to_user
     *
     * @return Entities\User 
     */
    public function getToUser()
    {
        return $this->to_user;
    }
}