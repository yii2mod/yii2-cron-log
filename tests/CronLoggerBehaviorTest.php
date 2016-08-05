<?php

namespace yii2mod\cron\tests;

use Yii;
use yii\console\Controller;
use yii2mod\cron\models\CronScheduleModel;

/**
 * Class CronLoggerBehaviorTest
 * @package yii2mod\cron\tests
 */
class CronLoggerBehaviorTest extends TestCase
{
    public function testCronLog()
    {
        Yii::$app->runAction('hello');
        $cronScheduleModel = CronScheduleModel::find()->one();

        $this->assertNotEmpty($cronScheduleModel, 'Cron log is empty!');
        $this->assertEquals(Controller::EXIT_CODE_NORMAL, $cronScheduleModel->status, 'Invalid cron status!');
    }
}