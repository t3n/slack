<?php

declare(strict_types=1);

namespace t3n\Slack\Tests\Unit;

use Maknz\Slack\Client;
use Maknz\Slack\Message;
use Neos\Flow\Tests\UnitTestCase;
use t3n\Slack\Service\SlackService;

class SlackServiceTest extends UnitTestCase
{

    /**
     * @var SlackService
     */
    protected $slackService;

    protected $configurations = [
        'default' => [
            'webhookUrl' => 'http://some-url.com/',
            'clientSettings' => ['channel' => '#foo-talk']
        ],
        'invalid' => [
            'clientSettings' => []
        ]
    ];

    public function setUp()
    {
        $this->slackService = new SlackService();
        $this->inject($this->slackService, 'configurations', $this->configurations);
    }

    /**
     * @test
     * @expectedException \Neos\Flow\Configuration\Exception\InvalidConfigurationException
     */
    public function webookUrlMustBePresentInConfiguration()
    {
        $this->slackService->createMessage('invalid');
    }

    /**
     * @test
     */
    public function clientConfigurationWillBeAppliedToTheClient()
    {
        $message = $this->slackService->createMessage('default');
        $this->assertEquals('#foo-talk', $message->getChannel());
    }

    /**
     * @test
     */
    public function instantiatedClientsWillBeCached()
    {
        $messageMock = $this->getMockBuilder(Message::class)->disableOriginalConstructor()->getMock();
        $client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->setMethods(['createMessage'])->getMock();
        $client->expects($this->once())->method('createMessage')->willReturn($messageMock);

        $this->inject($this->slackService, 'clients', ['cached' => $client]);

        $actualMessage = $this->slackService->createMessage('cached');
        $this->assertSame($messageMock, $actualMessage);
    }
}
