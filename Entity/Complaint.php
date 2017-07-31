<?php
/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 27.07.2017
 * Time: 14:50
 */

namespace Neogen\BouncerBundle\Entity;


class Complaint
{
    const TYPE_ABUSE = 'abuse';
    const TYPE_AUTH_FAILURE = 'auth-failure';
    const TYPE_FRAUD = 'fraud';
    const TYPE_NOT_SPAM = 'not-spam';
    const TYPE_OTHER = 'other';
    const TYPE_VIRUS = 'virus';

    /**
     * @var int
     */

    private $id;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string
     */
    private $emailAddress;

    /**
     *
     * @var \DateTime
     */
    private $complainedOn;

    /**
     * @var string
     */
    private $feedbackId;

    /**
     *
     * @var string
     */
    private $userAgent;

    /**
     *
     * @var string
     */
    private $complaintFeedbackType;

    /**
     *
     * @var \DateTime
     */
    private $arrivalDate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return \DateTime
     */
    public function getComplainedOn()
    {
        return $this->complainedOn;
    }

    /**
     * @return string
     */
    public function getFeedbackId()
    {
        return $this->feedbackId;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getComplaintFeedbackType()
    {
        return $this->complaintFeedbackType;
    }

    /**
     * @return string
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmailAddress($email)
    {
        $this->emailAddress = $email;
        return $this;
    }

    /**
     * @param string $messageId
     *
     * @return $this
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @param \DateTime $complainedOn
     *
     * @return $this
     */
    public function setComplainedOn($complainedOn)
    {
        $this->complainedOn = $complainedOn;
        return $this;
    }

    /**
     * @param string $feedbackId
     *
     * @return $this
     */
    public function setFeedbackId($feedbackId)
    {
        $this->feedbackId = $feedbackId;
        return $this;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @param string $complaintFeedbackType
     *
     * @return $this
     */
    public function setComplaintFeedbackType($complaintFeedbackType)
    {
        $this->complaintFeedbackType = $complaintFeedbackType;
        return $this;
    }

    /**
     * @param \DateTime $arrivalDate
     *
     * @return $this
     */
    public function setArrivalDate(\DateTime $arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
        return $this;
    }
}