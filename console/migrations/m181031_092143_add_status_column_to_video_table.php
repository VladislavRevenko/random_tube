<?php

use yii\db\Migration;

/**
 * Handles adding status to table `video`.
 */
class m181031_092143_add_status_column_to_video_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('video', 'status', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('video', 'status');
    }
}
