<?php

use yii\db\Migration;

/**
 * Class m181113_073631_rename_columns_and_add_columnsName
 */
class m181113_073631_rename_columns_and_add_columnsName extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('video', 'name', $this->string());
        $this->renameColumn('video', 'status', 'idStatus');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181113_073631_rename_columns_and_add_columnsName cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_073631_rename_columns_and_add_columnsName cannot be reverted.\n";

        return false;
    }
    */
}
