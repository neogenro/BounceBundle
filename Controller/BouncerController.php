<?php

namespace Neogen\BouncerBundle\Controller;

use Neogen\BouncerBundle\Entity\Bounce;
use Neogen\BouncerBundle\Entity\Complaint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Aws\Sns\Exception\InvalidSnsMessageException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BouncerController extends Controller
{
    /**
     * Aws callback action
     *
     * @return Response
     */
    public function indexAction()
    {
        $logger = $this->get('logger');

        $message = Message::fromRawPostData();

        $validator = new MessageValidator();

        try {
            $validator->validate($message);
        } catch (InvalidSnsMessageException $e) {
            $logger->error(
                'Invalid message: ',
                $e->getTrace()
            );
            throw new NotFoundHttpException();
        }

        $this->get('neogen.request_handler')->requestHandler($message);

        return new Response('');
    }
}