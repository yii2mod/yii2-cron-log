Cron log
=============
Component for logging cron jobs

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
This action is used to view list of executed commands. // http://project.com/admin/settings/cron


To log cron actions you should add behavior to all commands that should be logged.
```php
    /**
     * @return array behavior configurations.
    */
    public function behaviors()
    {
        return array(
            'cronLogger' => array(
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => array( // action names that should be logged
                    'index', 
                    'test
                ),
            ),
        );
    }
```
As the result, you will be able to view list of cron runs at ```http://project.com/admin/settings/cron``` which contains: 
* ID	auto-increment
* Job Code - name of the action
* Status	- return code (0 for success)
* Messages	- exception trace
* Date Created	
* Date Scheduled	
* Date Executed	
* Date Finished
