<?php

namespace paragraph1\phpFCM\Tests;

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Topic;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends PhpFcmTestCase
{
    /**
     * @var Client
     */
    private $fixture;

    public function testSendConstruesValidJsonForNotificationWithTopic()
    {
        $apiKey = 'key';
        $headers = array(
            'Authorization' => sprintf('key=%s', $apiKey)
        );

        $guzzle = \Mockery::mock(\GuzzleHttp\Client::class);
        $guzzle->shouldReceive('request')
            ->once()
            ->with(
                "post",
                Client::DEFAULT_API_URL,
                [
                    "headers" => $headers,
                    "json" => [
                        "to" => "/topics/test",
                        "priority" => "high"
                    ]
                ])
            ->andReturn(\Mockery::mock(ResponseInterface::class));


        $this->fixture->injectHttpClient($guzzle);
        $this->fixture->setApiKey($apiKey);

        $message = new Message();
        $message->addRecipient(new Topic('test'));

        $this->fixture->send($message);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = new Client();
    }

    public function testProxyUriOverridesDefaultUrl()
    {
        $proxy = 'my_nice_proxy_around_that_server';
        $this->fixture->setProxyApiUrl($proxy);

        $guzzle = \Mockery::mock(\GuzzleHttp\Client::class);
        $guzzle->shouldReceive('request')
            ->once()
            ->with(
                "post",
                $proxy,
                [
                    "headers" => ['Authorization' => 'key='],
                    "json" => [
                        "to" => "/topics/test",
                        "priority" => "high"
                    ]
                ])
            ->andReturn(\Mockery::mock(ResponseInterface::class));

        $this->fixture->injectHttpClient($guzzle);

        $message = new Message();
        $message->addRecipient(new Topic('test'));

        $this->fixture->send($message);
    }
}
