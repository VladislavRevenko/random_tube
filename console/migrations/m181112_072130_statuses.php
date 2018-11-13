<?php

use yii\db\Migration;

/**
 * Class m181112_072130_statuses
 */
class m181112_072130_statuses extends Migration
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
        echo "m181112_072130_statuses cannot be reverted.\n";

        return false;
    }*/

    public function up()
    {
        $this->createTable('directory_status', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
        ]);
    }

    public function down()
    {
        echo "m181112_070428_statuses cannot be reverted.\n";

        return false;
    }
}