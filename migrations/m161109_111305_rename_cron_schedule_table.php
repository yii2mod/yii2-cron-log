<?php

use yii\db\Migration;

class m161109_111305_rename_cron_schedule_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%CronSchedule}}', '{{%cron_schedule}}');
    }

    public function down()
    {
        $this->renameTable('{{%cron_schedule}}', '{{%CronSchedule}}');
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
