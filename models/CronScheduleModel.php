<?php

namespace yii2mod\cron\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/**
 * This is the model class for table "CronSchedule".
 *
 * @property string $id
 * @property string $jobCode
 * @property string $status
 * @property string $messages
 * @property string $dateCreated
 * @property string $dateScheduled
 * @property string $dateExecuted
 * @property string $dateFinished
 */
class CronScheduleModel extends ActiveRecord
{

    /**
     * Declares the name of the database table associated with this AR class.
     * @return string the table name
     */
    public static function tableName()
    {
        return '{{%CronSchedule}}';
    }

    /**
     * Returns the validation rules for attributes.
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [['messages'], 'string'],
            [['dateCreated', 'dateScheduled', 'dateExecuted', 'dateFinished'], 'safe'],
            [['jobCode'], 'string', 'max' => 255],
            ['status', 'integer'],
        ];
    }

    /**
     * Returns the attribute labels.
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cron', 'ID'),
            'jobCode' => Yii::t('cron', 'Job Code'),
            'status' => Yii::t('cron', 'Status'),
            'messages' => Yii::t('cron', 'Messages'),
            'dateCreated' => Yii::t('cron', 'Date Created'),
            'dateScheduled' => Yii::t('cron', 'Date Scheduled'),
            'dateExecuted' => Yii::t('cron', 'Date Executed'),
            'dateFinished' => Yii::t('cron', 'Date Finished'),
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
        $this->dateScheduled = new Expression('NOW()');
        $this->dateExecuted = new Expression('NOW()');

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
            $this->dateFinished = new Expression('NOW()');
            $this->status = $status;
            $this->messages = $messages;
            return $this->save();
        }
        return false;
    }
}



