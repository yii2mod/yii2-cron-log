Cron log
=============
Component for logging cron jobs

To use this extension, add to actions in your controller below code:

```php
    public function actions()
    {
        return [
            'cron' => 'yii2mod\cron\actions\CronLogAction',
        ];
    }
```
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
