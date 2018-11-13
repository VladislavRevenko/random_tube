<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "directory_status".
 *
 * @property int $id
 * @property string $status
 */
class DirectoryStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'directory_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
        ];
    }

    public function getVideo()
    {
        return $this->hasMany(Video::className(), ['id' => 'idStatus']);
    }

}
