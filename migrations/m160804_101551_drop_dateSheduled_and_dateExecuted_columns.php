<?php

use app\components\Migration;

class m160804_101551_drop_dateSheduled_and_dateExecuted_columns extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%CronSchedule}}', 'dateScheduled');
        $this->dropColumn('{{%CronSchedule}}', 'dateExecuted');

        $this->dropIndex('idx-CronSchedule-dateScheduled-status', '{{%CronSchedule}}');
        $this->createIndex('idx-CronSchedule-status', '{{%CronSchedule}}', 'status');
    }

    public function down()
    {
        $this->addColumn('{{%CronSchedule}}', 'dateScheduled', $this->timestamp()->null());
        $this->addColumn('{{%CronSchedule}}', 'dateExecuted', $this->timestamp()->null());

        $this->dropIndex('idx-CronSchedule-status', '{{%CronSchedule}}');
        $this->createIndex('idx-CronSchedule-dateScheduled-status', '{{%CronSchedule}}', ['dateScheduled', 'status']);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
