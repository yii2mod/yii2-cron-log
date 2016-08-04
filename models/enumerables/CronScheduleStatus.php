<?php

namespace yii2mod\cron\models\enumerables;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class CronScheduleStatus
 * @package yii2mod\cron\models\enumerables
 */
class CronScheduleStatus extends BaseEnum
{
    const SUCCESS = 0;
    const ERROR = 1;
    const RUN = 2;

    /**
     * @var string message category
     */
    public static $messageCategory = 'yii2mod-cron-log';

    /**
     * @var array
     */
    public static $list = [
        self::SUCCESS => 'Success',
        self::ERROR => 'Error',
        self::RUN => 'Run'
    ];
}