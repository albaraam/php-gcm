# PHP albaraam/php-gcm Library

A PHP library for sending messages to devices registered through Google Cloud Messaging.

For license information check the [LICENSE](LICENSE.md)-file.



Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist albaraam/php-gcm
```

or add

```json
"albaraam/php-gcm": "dev-master"
```

to the `require` section of your composer.json.


Usage
------------

```php

use albaraam\gcm\GCMNotification;
use albaraam\gcm\GCMMessage;
use albaraam\gcm\GCMClient;

$notification = new GCMNotification("Tilte","Body");
$notification->setIcon("noti");
$notification->setSound("water.mp3");
.....

$message = new GCMMessage($notification, ['foo'=>'bar', 'baz'=>[1,2,3]], "collapse-key-1");
$message->timeToLive(3600); // TTL 1 hour
.....

$gcm = new GCMClient("ids", "YOUR_API_KEY"); // "ids" field can contain a array/single registration token or a topic key
$response = $gcm->send($message);

var_dump($response);

```