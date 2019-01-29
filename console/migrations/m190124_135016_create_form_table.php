<?php

use yii\db\Migration;

/**
 * Handles the creation of table `form`.
 */
class m190124_135016_create_form_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('form', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('form');
    }
}
