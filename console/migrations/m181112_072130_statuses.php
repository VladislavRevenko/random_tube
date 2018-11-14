<?php

use yii\db\Migration;

/**
 * Class m181112_072130_statuses
 */
class m181112_072130_statuses extends Migration
{
    public function up()
    {
        $this->createTable('directory_statuses', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'value' => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('directory_statuses');
    }
}