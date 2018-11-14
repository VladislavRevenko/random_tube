<?php

namespace common\models;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $link_video
 * @property int $like
 * @property int $dislike
 * @property string $status_id
 * @property string $name
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
            [['link_video', 'status_id', 'name'], 'string', 'max' => 255],
            [['link_video'], 'required'],
            [['link_video'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название видео',
            'link_video' => 'Ссылка на youtube',
            'like' => 'Лайк',
            'dislike' => 'Дизлайк',
            'status_id' => 'Статус',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $dataCreateVideo = $this->attributes; //атрибуты нового видео
            $urlVideo = $dataCreateVideo['link_video'];

            $url_host = parse_url($urlVideo);
            //Если у нас в записи содержится только id  видео то пропускаем остальное
            if (empty($url_host['host'])) {
                return true;
            }

            if (!empty($url_host['host'])) {
                if ($url_host['host'] == 'www.youtube.com') {
                    if (!empty($url_host['query'])) {
                        $url_video = preg_replace('/v=/', '', $url_host['query']);
                        $this->link_video = $url_video;
                    }
                } elseif ($url_host['host'] == 'youtu.be') {
                    if (!empty($url_host['path'])) {
                        $url_video = preg_replace('/https:\/\/youtu.be\/\//', '', $url_host['path']);
                        $this->link_video = $url_video;
                    }
                }
                // Чекаем на уникальность
                if (Video::find()->where(['link_video'=>$url_video])->exists()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function getDirectoryStatus()
    {
        return $this->hasOne(DirectoryStatus::className(), ['id' => 'status_id']);
    }
}
