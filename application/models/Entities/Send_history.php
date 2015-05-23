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
     * Set user
     *
     * @param Entities\User $user
     * @return Send_history
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
     * @var Entities\User
     */
    private $to_user;


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
    /**
     * @var Entities\User
     */
    private $from_user;


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
}