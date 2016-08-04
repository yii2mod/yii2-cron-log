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

run
```php
php yii migrate/up --migrationPath=@yii2mod/cron/migrations
```
Please note that messages are wrapped with ```Yii::t()``` to support message translations, you should define default message source for them if you don't use i18n.
```php
'i18n' => [
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource'
        ],
    ],
],
```

Usage
------------
Error handler must be defined inside console config, it will be used to log exceptions into database.
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

To use this extension you need define action in any controller (for example /modules/admin/SettingsController.php)
```php
    public function actions()
    {
        return [
            'cron' => 'yii2mod\cron\actions\CronLogAction',
        ];
    }
```
This action is used to view list of executed commands. http://project.com/admin/settings/cron


To log cron actions you should add behavior to all commands that should be logged.
```php
    /**
     * @return array behavior configurations.
    */
    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => [ // action names that should be logged
                    'index' 
                ],
            ],
        ];
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