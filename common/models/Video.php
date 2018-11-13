<?php

namespace common\models;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $link_video
 * @property int $like
 * @property int $dislike
 * @property string $idStatus
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
            [['link_video', 'idStatus', 'name'], 'string', 'max' => 255],
            [['link_video'], 'required'],
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
            'idStatus' => 'Статус',
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
//            var_dump($changedAttributes); //старые атрибутты, при обновлении
//            var_dump($this->oldAttributes); // тоже новые атрибуты
            $dataCreateVideo = $this->attributes; //атрибуты нового видео
            $urlVideo = $dataCreateVideo['link_video'];

            $url_host = parse_url($urlVideo);

            if (!empty($url_host['host'])) {
                if ($url_host['host'] == 'www.youtube.com') {
                    $url_video = preg_replace('/v=/', '', $url_host['query']);
                    $this->link_video = "https://www.youtube.com/embed/" . $url_video;
                } elseif ($url_host['host'] == 'youtu.be') {
                    $url_video = preg_replace('/https:\/\/youtu.be\/\//', '', $url_host['path']);
                    $this->link_video = "https://www.youtube.com/embed" . $url_video;
                }
                $this->save();
                parent::afterSave($insert, $changedAttributes);
            }
        }
    }

    public function getDirectoryStatus()
    {
        return $this->hasOne(DirectoryStatus::className(), ['id' => 'idStatus']);
    }
}
