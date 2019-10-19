<?php

declare(strict_types=1);

namespace t3n\Slack\Service;

use Maknz\Slack\Client;
use Maknz\Slack\Message;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\Exception\InvalidConfigurationException;

/**
 * @Flow\Scope("singleton")
 */
class SlackService
{
    /**
     * @Flow\InjectConfiguration(path="configurations")
     *
     * @var mixed[]
     */
    protected $configurations;

    /**
     * @var Client[]
     */
    protected $clients = [];

    protected function getClient(string $configuration): Client
    {
        if (array_key_exists($configuration, $this->clients)) {
            return $this->clients[$configuration];
        }

        if (! array_key_exists($configuration, $this->configurations) || empty($this->configurations[$configuration]['webhookUrl'])) {
            throw new InvalidConfigurationException('The configuration preset does not exist or misses a webhookUrl setting');
        }

        $selectedConfiguration = $this->configurations[$configuration];
        $client = new Client($selectedConfiguration['webhookUrl'], $selectedConfiguration['clientSettings']);

        $this->clients[$configuration] = $client;

        return $client;
    }

    public function createMessage(string $configuration): Message
    {
        $client = $this->getClient($configuration);
        return $client->createMessage();
    }
}
