<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
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
