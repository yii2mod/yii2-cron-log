Cron log
=============

Component for logging cron jobs
Yii2 Image Component
==========

Provides methods for the dynamic manipulation of images. Various image formats such as JPEG, PNG, and GIF can be resized, cropped, rotated.

To use this extension, add to actions in your controller below code:

```php
    public function actions()
    {
        return [
            'cron' => 'yii2mod\cron\actions\CronLogAction',
        ];
    }
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
