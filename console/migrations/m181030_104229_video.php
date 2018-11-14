<?php

use yii\db\Migration;

/**
 * Class m181030_104229_video
 */
class m181030_104229_video extends Migration
{
    /**
     * {@inheritdoc}
     */
    /*public function safeUp()
    {

    }*/

    /**
     * {@inheritdoc}
     */
    /*public function safeDown()
    {
        echo "m181030_104229_video cannot be reverted.\n";

        return false;
    }*/


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('video', [
            'id' => $this->primaryKey(),
            'link_video' => $this->string(),
            'like' => $this->integer(),
            'dislike' => $this->integer(),
            'status_id' => $this->integer(),
            'name' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('video');
    }

}
