<?php

namespace yii2mod\cron\tests\data;

use yii\console\Controller;

/**
 * Class HelloController
 *
 * @package yii2mod\cron\tests\data
 */
class HelloController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'cronLogger' => [
                'class' => 'yii2mod\cron\behaviors\CronLoggerBehavior',
                'actions' => ['index'],
            ],
        ];
    }

    /**
     * This command echoes what you have entered as the message.
     *
     * @param string $message the message to be echoed
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
}
