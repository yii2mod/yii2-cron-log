<?php

namespace yii2mod\cron\models;

use Yii;
use yii\db\ActiveRecord;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/**
 * This is the model class for table "CronSchedule".
 *
 * @property string $id
 * @property string $jobCode
 * @property string $status
 * @property string $messages
 * @property string $dateCreated
 * @property string $dateFinished
 */
class CronScheduleModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cron_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['messages', 'string'],
            ['jobCode', 'string', 'max' => 255],
            ['status', 'integer'],
            [['dateCreated', 'dateFinished'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yii2mod-cron-log', 'ID'),
            'jobCode' => Yii::t('yii2mod-cron-log', 'Job Code'),
            'status' => Yii::t('yii2mod-cron-log', 'Status'),
            'messages' => Yii::t('yii2mod-cron-log', 'Messages'),
            'dateCreated' => Yii::t('yii2mod-cron-log', 'Date Created'),
            'dateFinished' => Yii::t('yii2mod-cron-log', 'Date Finished'),
        ];
    }

    /**
     * Start cron schedule
     *
     * @param string $jobCode
     * @param int $status
     * @param null $messages
     * @return bool
     */
    public function startCronSchedule($jobCode, $status = CronScheduleStatus::RUN, $messages = null)
    {
        $this->jobCode = $jobCode;
        $this->status = $status;
        $this->messages = $messages;
        $this->dateCreated = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i:s');

        return $this->save();
    }

    /**
     * End cron schedule
     *
     * @param string $status
     * @param null $messages
     *
     * @return boolean
     */
    public function endCronSchedule($status, $messages = null)
    {
        if ($this->id) {
            $this->dateFinished = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i:s');
            $this->status = $status;
            $this->messages = $messages;

            return $this->save();
        }

        return false;
    }
}