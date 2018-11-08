<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $link_video
 * @property int $like
 * @property int $dislike
 * @property string $status
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['like', 'dislike'], 'integer'],
            [['link_video', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_video' => 'Ссылка на youtube',
            'like' => 'Лайк',
            'dislike' => 'Дизлайк',
            'status' => 'Статус',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDropDownStatus()
    {
        $status = [
            'active' => 'Опубликовано',
            'archive' => 'Не опубликовано'
        ];

        return $status;
    }
}
