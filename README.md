Getting started with MainSMSBundle
=============

[MainSMS](http://mainsms.ru/)

## Prerequisites

This version of the bundle requires Symfony 2.1+ and Doctrine ORM 2.2+

## Installation

Installation is a quick 3 step process:

1. Download KarserMainSMSBundle using composer
2. Enable the Bundle
3. Configure the KarserMainSMSBundle

### Step 1: Download KarserMainSMSBundle using composer

Add KarserMainSMSBundle in your composer.json:

```js
{
    "require": {
        "karser/mainsms-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php ./composer.phar update
```

Composer will install the bundle to your project's `vendor/karser` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Karser\MainSMSBundle\KarserMainSMSBundle(),
    );
}
```

### Step 3: Configure the KarserMainSMSBundle

Add the following configuration to your `config.yml` file according to which type
of datastore you are using.

``` yaml
# app/config/config.yml
karser_main_sms:
    project: "%main_sms_project%"
    key: "%main_sms_key%"
    use_ssl: "%main_sms_use_ssl%"
    test_mode: "%main_sms_test_mode%"
```

``` yaml
# app/config/parameters.yml
parameters:
    main_sms_project:   ~ # project name
    main_sms_key:       ~ # sms key
    main_sms_use_ssl:   ~ # true or false
    main_sms_test_mode: ~ # true or false
```

### Usage Steps
#### Basic usage
You can send message directly:
``` php
$MainSMSModel = $this->get('karser.main_sms.model');
//or use getter trait
use \Karser\MainSMSBundle\Model\Getter;
$MainSMSModel = $this->getMainSmsModel($this->container);
//send message
$MainSMSModel->messageSend($number, $message, $sender);
```
#### Schedule the message
It maps the message to SMSTask entity and stores to the database
``` php
$MainSMSManager = $this->get('karser.main_sms.manager');
//or use getter trait
use \Karser\MainSMSBundle\Manager\Getter;
$MainSMSManager = $this->getMainSmsManager($this->container);
//schedule message
$MainSMSManager->schedule($number, $message, $sender);
```
You can send it later by cli command:
```
$ app/console mainsms:send
> Balance is 6.45
> Messages to send 1
.
Done.
```
