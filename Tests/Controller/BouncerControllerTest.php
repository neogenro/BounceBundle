<?php

namespace Neogen\BouncerBundle\Tests\Controller;

use Aws\Sns\Message;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Neogen\BouncerBundle\Service\RequestHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BouncerControllerTest extends WebTestCase
{
    private $swiftMailerMock;
    /** @var  RequestHandler */
    private $requestHandler;
    /** @var  Registry */
    private $registry;
    private $commonData = [
        'Message' => '',
        'MessageId' => '',
        'Timestamp' => '',
        'TopicArn' => '',
        'Type' => '',
        'Signature' => '',
        'SigningCertURL' => '',
        'SignatureVersion' => '',
        'Token' => '',
    ];

    protected function setUp()
    {
        parent::setUp();
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->swiftMailerMock = $this->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();
        $this->swiftMailerMock
            ->expects($this->any())
            ->method('send')
            ->withAnyParameters()
            ->willReturn(1);

        /** @var Registry $registry */
        $this->registry = static::$kernel->getContainer()->get('doctrine');

        $httpClientMock = $this->getMockBuilder('Http\Client\HttpClient')
            ->disableOriginalConstructor()
            ->getMock();
        $httpClientMock
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn(true);
        $requestMock = $this->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $messageFactoryMock = $this->getMockBuilder('Http\Message\MessageFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $messageFactoryMock
            ->expects($this->any())
            ->method('createRequest')
            ->willReturn($requestMock);

        $this->requestHandler = new RequestHandler($this->registry, $httpClientMock, $messageFactoryMock);
    }

    public function testSubscribeConfirmation()
    {
        $data = $this->commonData;
        $data['Type'] = 'SubscribeConfirmation';
        $data['SubscribeURL'] = '';
        $messageMock = new Message($data);
        $this->requestHandler->requestHandler($messageMock);
    }

    public function testUnsubscribeConfirmation()
    {
        $data = $this->commonData;
        $data['Type'] = 'UnsubscribeConfirmation';
        $data['SubscribeURL'] = '';
        $messageMock = new Message($data);
        $this->requestHandler->requestHandler($messageMock);
    }

    public function testBounce()
    {
        $data = $this->commonData;
        $data['Type'] = 'Bounce';
        $data['bounce'] = [
            'bouncedRecipients' => [
                ['emailAddress' => 'test-bounced@email.com'],
            ],
            'bounceType' => 'Permanent'
        ];
        $messageMock = new Message($data);
        $this->requestHandler->requestHandler($messageMock);

        $bounced = $this->registry->getManager()
            ->getRepository('BouncerBundle:Bounce')
            ->findOneBy(['emailAddress' => 'test-bounced@email.com']);
        $this->assertNotNull($bounced);
    }

    public function testComplaint()
    {
        $data = $this->commonData;
        $data['Type'] = 'Complaint';
        $data['complaint'] = [
            'complainedRecipients' => [
                [
                    'emailAddress' => 'test-complained@test.com'
                ]
            ],
            'timestamp' => '',
            'feedbackId' => 'test_feedback_id',
            'userAgent' => 'test_user_agent',
            'complaintFeedbackType' => 'test_user_agent'
        ];
        $messageMock = new Message($data);
        $this->requestHandler->requestHandler($messageMock);

        $complaint = $this->registry->getManager()
            ->getRepository('BouncerBundle:Complaint')
            ->findOneBy(['emailAddress' => 'test-complained@test.com']);
        $this->assertNotNull($complaint);
    }
}
