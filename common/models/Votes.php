<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "votes".
 *
 * @property int $id
 * @property int $video_id
 * @property string $ip
 * @property string $useragent
 * @property int $vote
 * @property string $created
 */
class Votes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_id', 'vote'], 'integer'],
            [['created'], 'safe'],
            [['ip', 'useragent'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => 'ID видео',
            'ip' => 'IP',
            'useragent' => 'Пользователь',
            'vote' => 'Голос',
            'created' => 'Создано',
        ];
    }
}
