<?php

use yii\db\Migration;

/**
 * Handles adding template to table `categories`.
 */
class m190128_114138_add_template_column_to_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories', 'template_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('categories', 'template_id');
    }
}
