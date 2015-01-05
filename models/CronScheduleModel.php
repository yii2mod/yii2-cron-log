<?php
namespace yii2mod\cron\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%CronSchedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['messages'], 'string'],
            [['dateCreated', 'dateScheduled', 'dateExecuted', 'dateFinished'], 'safe'],
            [['jobCode'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @author   Roman Protsenko <protsenko@zfort.com>
     *
     * @param string $jobCode
     * @param string $status
     * @param null   $messages
     *
     * @internal param string $message
     * @return boolean
     */
    public function startCronSchedule($jobCode, $status = null, $messages = null)
    {
        if ($status === null) {
            $status = 'running';
        }
        $this->jobCode = $jobCode;
        $this->status = $status;
        $this->messages = $messages;

        $this->dateScheduled = new Expression('NOW()');
        $this->dateExecuted = new Expression('NOW()');
        return $this->save();
    }

    /**
     *
     * @author   Roman Protsenko <protsenko@zfort.com>
     *
     * @param string $status
     * @param null   $messages
     *
     * @internal param string $message
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



