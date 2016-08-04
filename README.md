Yii2 Cron Log
=============
Component for logging cron jobs

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-cron-log/v/stable)](https://packagist.org/packages/yii2mod/yii2-cron-log) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-cron-log/downloads)](https://packagist.org/packages/yii2mod/yii2-cron-log) [![License](https://poser.pugx.org/yii2mod/yii2-cron-log/license)](https://packagist.org/packages/yii2mod/yii2-cron-log)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2mod/yii2-cron-log "*"
```

or add

```json
"yii2mod/yii2-cron-log": "*"
```

to the require section of your composer.json.

Configuration
-----------------------

**Database Migrations**

Before using this extension, we'll also need to prepare the database.
```php
php yii migrate/up --migrationPath=@yii2mod/cron/migrations
```

**Error Handler and File Mutex Setup**

Error handler must be defined inside console config, it will be used to log exceptions into database.

> FileMutex implements mutex "lock" mechanism via local file system files.

Add the following code to your console application configuration:
```php
'components' => [
    'errorHandler' => [
        'class' => 'yii2mod\cron\components\ErrorHandler',
    ],
    'mutex' => [
        'class' => 'yii\mutex\FileMutex'
    ],
],
```

Usage
----------
1) To access the list of executed commands, you need to define `CronLogAction` in any controller (for example /modules/admin/SettingsController.php):

```php
    public function actions()
    {
        return [
            'cron' => 'yii2mod\cron\actions\CronLogAction',
        ];
    }
```

> This action is used to view list of executed commands: http://project.com/admin/settings/cron


2) To log cron actions you should add behavior to all commands that should be logged. In the following example `CronLoggerBehavior` will be log the `index` action.

```php

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 */
class HelloController extends Controller
{
    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => ['index']
            ],
        ];
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
}
```

## Internationalization

All text and messages introduced in this extension are translatable under category 'yii2mod-cron-log'.
You may use translations provided within this extension, using following application configuration:

```php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'yii2mod-cron-log' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/cron/messages',
                ],
                // ...
            ],
        ],
        // ...
    ],
    // ...
];
```
