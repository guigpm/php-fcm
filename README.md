# phpFCM
[![Build Status](https://travis-ci.org/guigpm/php-fcm.svg?branch=master)](https://travis-ci.org/guigpm/php-fcm)
[![Coverage Status](https://coveralls.io/repos/github/guigpm/php-fcm/badge.svg?branch=master)](https://coveralls.io/github/guigpm/php-fcm?branch=master)
[![Latest Stable Version](https://poser.pugx.org/guigpm/php-fcm/v/stable)](https://packagist.org/packages/guigpm/php-fcm)
[![Total Downloads](https://poser.pugx.org/guigpm/php-fcm/downloads)](https://packagist.org/packages/guigpm/php-fcm)
[![License](https://poser.pugx.org/guigpm/php-fcm/license)](https://packagist.org/packages/guigpm/php-fcm)


A fork of the phpFCM project. See the original at: https://github.com/Paragraph1/php-fcm


PHP application server implementation for Firebase Cloud Messaging.
- supports device and topic messages
- currently this app server library only supports sending Messages/Notifications via HTTP.
- thanks to guzzle our library answers in PSR7 compatible response objects
- see the full docs on firebase cloud messaging here : https://firebase.google.com/docs/cloud-messaging/
- Firebase Cloud Messaging HTTP Protocol: https://firebase.google.com/docs/cloud-messaging/http-server-ref#send-downstream for in-depth description


#Setup
The recommended way of installing is using Composer.

command line
```
composer require guigpm/php-fcm
```

composer.json
```
"require": {
    "guigpm/php-fcm": "*"
}
```

#Send to Device
also see https://firebase.google.com/docs/cloud-messaging/downstream
```php
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

require_once 'vendor/autoload.php';

$apiKey = 'YOUR SERVER KEY';
$client = new Client();
$client->setApiKey($apiKey);
$client->injectHttpClient(new \GuzzleHttp\Client());

$note = new Notification('test title', 'testing body');
$note->setIcon('notification_icon_resource_name')
    ->setColor('#ffffff')
    ->setBadge(1);

$message = new Message();
$message->addRecipient(new Device('your-device-token'));
$message->setNotification($note)
    ->setData(array('someId' => 111));

$response = $client->send($message);
var_dump($response->getStatusCode());
```

#Send to topic
also see https://firebase.google.com/docs/cloud-messaging/topic-messaging
```php
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Topic;
use paragraph1\phpFCM\Notification;

require_once 'vendor/autoload.php';


$apiKey = 'YOUR SERVER KEY';
$client = new Client();
$client->setApiKey($apiKey);

$message = new Message();
$message->addRecipient(new Topic('your-topic'));
//select devices where has 'your-topic1' && 'your-topic2' topics
$message->addRecipient(new Topic(['your-topic1', 'your-topic2']));
$message->setNotification(new Notification('test title', 'testing body'))
    ->setData(array('someId' => 111));

$response = $client->send($message);
var_dump($response->getStatusCode());
```