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

    public static $list = [
        self::ERROR => 'Error',
        self::RUN => 'Run',
        self::SUCCESS => 'Complete',
    ];
}