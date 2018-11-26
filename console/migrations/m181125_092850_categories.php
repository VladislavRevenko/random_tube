<?php

use yii\db\Migration;

/**
 * Class m181125_092850_categories
 */
class m181125_092850_categories extends Migration
{
    public function up()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
        ]);

        $this->addColumn('video', 'category_id', $this->integer()->defaultValue(null));
    }

    public function down()
    {
        $this->dropTable('categories');
        $this->dropColumn('video', 'category_id');
    }
}
