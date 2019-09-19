<?php

use yii\db\Migration;

/**
 * Class m181114_123137_create_post_votes
 */
class m181114_123137_create_post_votes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('votes', [
            'id' => $this->primaryKey(),
            'video_id' => $this->integer(),
            'ip' => $this->string(),
            'useragent' => $this->string(),
            'vote' => $this->integer()->defaultValue(0),
            'created' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('Votes');
    }
}
