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
            'status_id' => $this->integer(),
            'name' => $this->string(),
            'rating' => $this->integer()->defaultValue(0),
            'views' => $this->integer()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('video');
    }

}
