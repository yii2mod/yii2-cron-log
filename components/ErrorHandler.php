<?php

namespace yii2mod\cron\components;

use Yii;
use yii\web\HttpException;
use yii2mod\cron\models\CronScheduleModel;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/**
 * Class ErrorHandler
 * @package yii2mod\cron\components
 */
class ErrorHandler extends \yii\console\ErrorHandler
{
    /**
     * @var CronScheduleModel model
     */
    public $schedule;

    /**
     * Logs the given exception
     *
     * @param \Exception $exception the exception to be logged
     */
    public function logException($exception)
    {
        $category = get_class($exception);
        if ($exception instanceof HttpException) {
            $category = 'yii\\web\\HttpException:' . $exception->statusCode;
        } elseif ($exception instanceof \ErrorException) {
            $category .= ':' . $exception->getSeverity();
        }
        if ($this->schedule) {
            $this->schedule->endCronSchedule(CronScheduleStatus::ERROR, (string)$exception);
            $this->schedule = null;
        }
        \Yii::error((string)$exception, $category);
    }

} 
