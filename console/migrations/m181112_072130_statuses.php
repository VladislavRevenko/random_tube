<?php

use yii\db\Migration;

/**
 * Class m181112_072130_statuses
 */
class m181112_072130_statuses extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('directory_statuses', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'value' => $this->integer()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('directory_statuses');
    }
}