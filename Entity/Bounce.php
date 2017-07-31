<?php

namespace Neogen\BouncerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * bounce
 * @ORM\Entity()
 *
 */
class Bounce
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $emailAddress;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_time_bounce", type="datetime")
     */
    private $lastTimeBounce;

    /**
     * @var integer
     * @ORM\Column(name="bounce_count", type="integer")
     */
    private $bounceCount;

    /**
     * @var boolean
     * @ORM\Column(name="permanent", type="boolean")
     */
    private $permanent;


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
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return bounce
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set lastTimeBounce
     *
     * @param \DateTime $lastTimeBounce
     * @return bounce
     */
    public function setLastTimeBounce($lastTimeBounce)
    {
        $this->lastTimeBounce = $lastTimeBounce;

        return $this;
    }

    /**
     * Get lastTimeBounce
     *
     * @return \DateTime 
     */
    public function getLastTimeBounce()
    {
        return $this->lastTimeBounce;
    }

    /**
     * Set bounceCount
     *
     * @param integer $bounceCount
     * @return bounce
     */
    public function setBounceCount($bounceCount)
    {
        $this->bounceCount = $bounceCount;

        return $this;
    }

    /**
     * Get bounceCount
     *
     * @return integer 
     */
    public function getBounceCount()
    {
        return $this->bounceCount;
    }

    /**
     * Set permanent
     *
     * @param boolean $permanent
     * @return bounce
     */
    public function setPermanent($permanent)
    {
        $this->permanent = $permanent;

        return $this;
    }

    /**
     * Get permanent
     *
     * @return boolean 
     */
    public function getPermanent()
    {
        return $this->permanent;
    }
}
