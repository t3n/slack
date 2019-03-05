[![Build Status](https://travis-ci.com/t3n/slack.svg?branch=master)](https://travis-ci.com/t3n/slack)

# t3n.Slack
Flow Package to send messages to Slack. This is package wraps the [maknz/slack](https://github.com/maknz/slack) library.

Simply install the package via composer:

```bash
composer require "t3n/slack"
```

## Configuration
In order to send messages to Slack you need to add an incoming WebHook to your Slack workspace. Read more about it here [https://api.slack.com/incoming-webhooks](https://api.slack.com/incoming-webhooks)

As the incoming webhooks are treated as Slack Apps they are bound to a single channel. Therefore you can configure multiple "presets" to use several webhooks:

````yaml
t3n:
  Slack:
    myPreset:   # you preset name
      webhookUrl: 'https://hooks.slack.com/services/...'
      clientSettings: []   # additional client configurations
````

Read more about the possible client settings and options here: https://github.com/maknz/slack#settings

## Sending messages

```php

/**
 * @Flow\Inject
 * @var \t3n\Slack\Service\SlackService
 */
protected $slackService;

public function sendAMessage()
{
    $message = $this->slackService->createMessage('myPreset');
    $message->send('some message');
}
```

If you create a message you need to pass the preset name. Check [maknz/slack documentation](https://github.com/maknz/slack#sending-messages) for all options that are
available on the message object

