<?php

namespace common\models;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $link_video
 * @property string $status_id
 * @property string $category_id
 * @property string $name
 * @property int $rating
 * @property int $views
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
            [['rating', 'views', 'status_id', 'category_id'], 'integer'],
            [['link_video', 'name'], 'string', 'max' => 255],
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
            'status_id' => 'Статус',
            'category_id' => 'Категория',
            'rating' => 'Рейтинг',
            'views' => 'Просмотров',
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
                        $pattern = ['/&feature=share/', '/v=/'];
                        $url_video = preg_replace($pattern, '', $url_host['query']);

                    }
                } elseif ($url_host['host'] == 'youtu.be') {
                    if (!empty($url_host['path'])) {
                        $url_video = preg_replace('/https:\/\/youtu.be\/\//', '', $url_host['path']);
                    }
                }
                $this->link_video = $url_video;
                $this->status_id = 1;
                // Чекаем на уникальность
                if (Video::find()->where(['link_video' => $url_video])->exists()) {
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

    public function getVotes()
    {
        return $this->hasOne(Votes::className(), ['id' => 'video_id']);
    }

    public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }
}
