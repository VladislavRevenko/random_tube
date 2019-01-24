<?php

use yii\db\Migration;

/**
 * Class m181125_092850_categories
 */
class m181125_092850_categories extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
        ], $tableOptions);

        $this->addColumn('video', 'category_id', $this->integer()->defaultValue(null));
    }

    public function down()
    {
        $this->dropTable('categories');
        $this->dropColumn('video', 'category_id');
    }
}
