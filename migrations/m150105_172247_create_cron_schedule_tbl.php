<?php

use yii\db\Schema;
use yii\db\Migration;

class m150105_172247_create_cron_schedule_tbl extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%CronSchedule}}',
            [
                'id' => Schema::TYPE_PK,
                'jobCode' => Schema::TYPE_STRING . '(255) NULL DEFAULT NULL',
                'status' => Schema::TYPE_STRING . '(255) NULL DEFAULT NULL',
                'messages' => Schema::TYPE_TEXT . ' NULL',
                'dateCreated' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
                'dateScheduled' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
                'dateExecuted' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
                'dateFinished' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            ],
            $tableOptions
        );

        $this->createIndex('IDX_CRON_SCHEDULE_JOB_CODE', '{{%CronSchedule}}', ['jobCode']);
        $this->createIndex('IDX_CRON_SCHEDULE_SCHEDULED_AT_STATUS', '{{%CronSchedule}}', ['dateScheduled', 'status']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%CronSchedule}}');
    }
}
