![alt text](https://static.wixstatic.com/media/375ee2_11b74c3cb74e417e8278e40d10bf497b~mv2.png "MailKitchen")

# Official MailKitchen PHP Wrapper API

![Current Version](https://img.shields.io/badge/version-1.0-green.svg)

This repo contains the PHP wrapper for the MailKitchen API.

## Requirements

`PHP >= 5.4`

## Installation

``` bash
composer require mailkitchen/api-wrapper-php
```

## Getting Started!

Login using your "api_key" and "api_key_secret" enabled in your MailKitchen's account in our platform here:
https://mail.mailkitchen.com

Initialize your MailKitchen Client:

``` php
<?php

$mk = new \Mailkitchen\Client(['api_key' => '<your_api_key>' , 'api_secret_key' => '<your_api_secret_key>']);

?>
```


## Make your first call

``` php
<?php
require 'vendor/autoload.php';

// Generate an object MailKitchen by logging in
$mk = new \Mailkitchen\Client(['api_key' => '<your_api_key>' , 'api_secret_key' => '<your_api_secret_key>']);

// Get your campaigns
$response = $mk->get(\Mailkitchen\Resources::CAMPAIGNS);

```

#### Requests
The main client functions are available througout get, post, put and delete methods with parameters passed as arguments.

| Resources  | Action                                                      | 
|------------|-------------------------------------------------------------|
| `get()`    | Retrieve details or do some actions for resources specified |
| `post()`   | Create resource specified                                   |
| `put()`    | Update resource specified                                   |
| `delete()` | Delete resource specified                                   |



#### Available Resources

| Resources           | Action                                               | 
|---------------------|------------------------------------------------------|
| `SUBSCRIBERS`       | To handle subscribers                                |
| `CUSTOM_FIELDS`     | To handle custom fields                              |
| `MAILING_LISTS`     | To handle mailing lists                              |
| `UNSUBSCRIBE_LISTS` | To handle unsubscribe lists                          |
| `SEGMENTS`          | To handle segments                                   |
| `CAMPAIGNS`         | To handle campaigns                                  |
| `SENDERS`           | To handle senders                                    |
| `RELATIONSHIPS`     | To handle some resources linked to another resources |
| `TOOLS`             | To do some actions on resources specified            |
| `CLEANING`          | To clean hardbounces only in mailing lists           |
| `EXPORT`            | To export data from mailing and unsubscribe lists    |
| `REFRESH`           | To refresh the count of subscribers in segments      |
| `VALIDATE`          | To validate campaigns with spamassassin tool         |
| `STATISTICS`        | To get statistics                                    |
| `GEOLOCATION`       | To get statistics by geolocation                     |
| `CLIENT`            | To get statistics by email client                    |
| `GLOBALS`           | To get statistics with globals stats                 |
| `PROVIDER`          | To get statistics by providers                       |

#### Documentation

You will find a documentation here to use this API: https://api.mailkitchen.com/docs/?php#introduction