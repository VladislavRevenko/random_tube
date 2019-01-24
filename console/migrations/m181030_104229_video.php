<?php

use yii\db\Migration;

/**
 * Class m181030_104229_video
 */
class m181030_104229_video extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('video', [
            'id' => $this->primaryKey(),
            'link_video' => $this->string(),
            'status_id' => $this->integer(),
            'name' => $this->string(),
            'rating' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('video');
    }

}
