<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profiler_logs}}`.
 */
class m220211_094258_create_profiler_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("CREATE TABLE IF NOT EXISTS `details` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `app` varchar(32) DEFAULT NULL,
          `label` varchar(64) DEFAULT NULL,
          `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `perfdata` mediumblob,
          PRIMARY KEY (`id`),
          KEY `timestamp` (`timestamp`),
          KEY `app` (`app`),
          KEY `label` (`label`),
          KEY `timestamp_label_idx` (`timestamp`,`label`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%profiler_logs}}');
    }
}
