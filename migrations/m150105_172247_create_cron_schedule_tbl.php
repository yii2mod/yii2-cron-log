<?php

use yii\db\Migration;

/**
 * Class m150105_172247_create_cron_schedule_tbl
 */
class m150105_172247_create_cron_schedule_tbl extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%CronSchedule}}', [
            'id' => $this->primaryKey(),
            'jobCode' => $this->string()->null(),
            'status' => $this->smallInteger()->notNull(),
            'messages' => $this->text(),
            'dateCreated' => $this->timestamp()->null(),
            'dateScheduled' => $this->timestamp()->null(),
            'dateExecuted' => $this->timestamp()->null(),
            'dateFinished' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('idx-CronSchedule-jobCode', '{{%CronSchedule}}', 'jobCode');
        $this->createIndex('idx-CronSchedule-dateScheduled-status', '{{%CronSchedule}}', ['dateScheduled', 'status']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%CronSchedule}}');
    }
}
