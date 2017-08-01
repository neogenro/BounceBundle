<?php
/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 28.07.2017
 * Time: 09:07
 */

namespace Neogen\BouncerBundle\Service;


use Aws\Sns\Message;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Neogen\BouncerBundle\Entity\Bounce;
use Neogen\BouncerBundle\Entity\Complaint;

class RequestHandler
{
    const MESSAGE_TYPE_SUBSCRIBE            = 'SubscriptionConfirmation';
    const MESSAGE_TYPE_UNSUBSCRIBE          = 'UnsubscribeConfirmation';
    const MESSAGE_TYPE_BOUNCE               = 'Bounce';
    const MESSAGE_TYPE_COMPLAINT            = 'Complaint';
    const MESSAGE_TYPE_NOTIFICATION         = 'Notification';

    /** @var \Doctrine\Common\Persistence\ObjectManager|object */
    private $em;
    /** @var HttpClient */
    private $client;
    /** @var MessageFactory */
    private $messageFactory;

    /**
     * RequestHandler constructor.
     *
     * @param Registry $registry
     * @param HttpClient $client
     * @param MessageFactory $messageFactory
     */
    public function __construct(Registry $registry, HttpClient $client, MessageFactory $messageFactory)
    {
        $this->em = $registry->getManager();
        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Request handler.
     *
     * @param Message $message
     */
    public function requestHandler(Message $message)
    {
        switch($message['Type']){
            case self::MESSAGE_TYPE_SUBSCRIBE:
                $request = $this->messageFactory->createRequest('GET', $message['SubscribeURL']);
                $this->client->sendRequest($request);
                break;
            case self::MESSAGE_TYPE_UNSUBSCRIBE:
                $request = $this->messageFactory->createRequest('GET', $message['SubscribeURL']);
                $this->client->sendRequest($request);
                break;
            case self::MESSAGE_TYPE_NOTIFICATION:
                $content = json_decode($message['Message'], true);

                switch($content['notificationType']){
                    case self::MESSAGE_TYPE_BOUNCE:
                        $this->handleBounce($content);
                        break;
                    case self::MESSAGE_TYPE_COMPLAINT:
                        $this->handleComplaint($content);
                        break;
                }
                break;
        }
    }

    /**
     * @param array $message
     * @return bool
     */
    private function handleBounce(array $message)
    {
        if(isset($message['bounce'])){
            foreach ($message['bounce']['bouncedRecipients'] as $bouncedRecipient) {
                $email = $bouncedRecipient['emailAddress'];
                $bounce = $this->em->getRepository('BouncerBundle:Bounce')->findOneBy(['emailAddress' => $email]);
                if (null === $bounce) {
                    $bounce = new Bounce();
                    $bounce->setBounceCount(0);
                    $bounce->setEmailAddress($email);
                }
                $bounce->setBounceCount($bounce->getBounceCount() + 1);
                $bounce->setLastTimeBounce(new \DateTime());
                $bounce->setPermanent(($message['bounce']['bounceType'] == 'Permanent'));
                $this->em->persist($bounce);
            }
            $this->em->flush();
        }

        return true;
    }

    /**
     * @param array $message
     * @return bool
     */
    private function handleComplaint(array $message)
    {
        if(isset($message['complaint'])){
            foreach ($message['complaint']['complainedRecipients'] as $complainedRecipient) {

                $complaint = new Complaint();
                $complaint
                    ->setEmailAddress($complainedRecipient['emailAddress'])
                    ->setComplainedOn(new \DateTime($message['complaint']['timestamp']))
                    ->setFeedbackId($message['complaint']['feedbackId']);
                if (isset($message['complaint']['userAgent'])) {
                    $complaint->setUserAgent($message['complaint']['userAgent']);
                }
                if (isset($message['complaint']['complaintFeedbackType'])) {
                    $complaint->setComplaintFeedbackType($message['complaint']['complaintFeedbackType']);
                }
                if (isset($message['complaint']['arrivalDate'])) {
                    $complaint->setArrivalDate(new \DateTime($message['complaint']['arrivalDate']));
                }
                $this->em->persist($complaint);
            }
            $this->em->flush();
        }

        return true;
    }
}