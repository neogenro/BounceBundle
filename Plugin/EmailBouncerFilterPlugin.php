<?php
/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 26.07.2017
 * Time: 17:08
 */

namespace Neogen\BouncerBundle\Plugin;


use Acme\UserBundle\Entity\bounce;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailBouncerFilterPlugin implements \Swift_Events_SendListener
{
    /** @var \Doctrine\Common\Persistence\ObjectManager|object */
    private $em;
    /** @var  bool */
    private $bounceEnabled;
    /** @var  bool */
    private $complaintEnabled;
    /** @var array */
    private $blacklisted = [];
    /**
     * EmailBouncerFilterListener constructor.
     * @param Registry $doctrine
     * @param bool $bounceEnabled
     * @param bool $complaintEnabled
     */
    public function __construct(Registry $doctrine, $bounceEnabled, $complaintEnabled)
    {
        $this->em = $doctrine->getManager();
        $this->bounceEnabled = $bounceEnabled;
        $this->complaintEnabled = $complaintEnabled;
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        $message = $evt->getMessage();

        $message->setTo($this->filterForBlacklisted($message->getTo()));
        $message->setCc($this->filterForBlacklisted($message->getCc()));
        $message->setBcc($this->filterForBlacklisted($message->getBcc()));
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
        $evt->setFailedRecipients(array_keys($this->blacklisted));
    }

    /**
     * Ignore email address if exists in the bounce or complaint list.
     *
     * @param $recipients
     * @return mixed
     */

    private function filterForBlacklisted($recipients)
    {
        if(!is_array($recipients)){
            return $recipients;
        }

        $emails = array_keys($recipients);

        if(count($emails)){
            foreach($emails as $email){
                if($this->isBounced($email) || $this->isComplained($email)){
                    $this->blacklisted[$email] = $recipients[$email];
                    unset($recipients[$email]);
                }
            }
        }

        return $recipients;
    }

    /**
     * Check if email is on the bounced list.
     *
     * @param $email
     * @return bool
     */
    private function isBounced($email)
    {
        if(!$this->bounceEnabled){
            return false;
        }

        return null !== $this->em->getRepository('BouncerBundle:Bounce')->findOneBy(['emailAddress' => $email, 'permanent' => true]);
    }

    /** Check if email is on the complainers list.
     *
     * @param $email
     * @return bool
     */
    private function isComplained($email)
    {
        if(!$this->complaintEnabled){
            return false;
        }

        return null !== $this->em->getRepository('BouncerBundle:Complaint')->findOneBy(['emailAddress' => $email]);
    }
}