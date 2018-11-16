<?php

use yii\db\Migration;

/**
 * Class m181114_123137_create_post_votes
 */
class m181114_123137_create_post_votes extends Migration
{
    public function up()
    {
        $this->createTable('Votes', [
            'id' => $this->primaryKey(),
            'video_id' => $this->integer(),
            'ip' => $this->string(),
            'useragent' => $this->string(),
            'vote' => $this->integer()->defaultValue(0),
            'created' => $this->dateTime(),
        ]);
    }

    public function down()
    {
        $this->dropTable('Votes');
    }
}
