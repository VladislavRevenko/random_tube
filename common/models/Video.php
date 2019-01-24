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

    public $allowedHosts = array(
        "youtube.com",
        "www.youtube.com",
        "youtu.be",
    );

    public $video_id;

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
            if (empty($dataCreateVideo['link_video'])) {
                //Если у нас в записи содержится только id  видео то пропускаем остальное
                if (!empty($dataCreateVideo['video_id'])) {
                    return true;
                } else {
                    return false;
                }
            }

            $urlVideo = $dataCreateVideo['link_video'];
            $url = parse_url($urlVideo);

            if (empty($url['host'])) {
                $this->addError('link_video', 'Неправильная ссылка на видео');
                return false;
            }

            if (!empty($url['host'])) {
                if (array_search($url['host'], $this->allowedHosts)!==false) {
                    $video_id = null;

                    if ($url['host'] == 'www.youtube.com' || $url['host'] == 'youtube.com') {
                        $query_params = explode('&', $url['query']);
                        foreach($query_params as $param) {
                            if (substr($param, 0, 2)=='v=') {
                                $video_id = substr($param, 2);
                            }
                        }
                    } elseif ($url['host'] == 'youtu.be') {
                        $path = explode('/', $url['path']);
                        if (!empty($path[1])) {
                            $video_id = $path[1];
                        }
                    }
                    if (!empty($video_id)) {
                        if (Video::find()->where(['link_video' => $video_id])->exists()) {
                            $this->addError('link_video', 'Это видео уже есть на сайте');
                            return false;
                        }
                        $this->link_video = $video_id;
                        return true;
                    } else {
                        $this->addError('link_video', 'Неправильная ссылка на видео');
                        return false;
                    }

                } else {
                    $this->addError('link_video', 'Поддерживаются только ссылки с www.youtube.com, youtube.com и youtu.be');
                    return false;
                }
            }
            // return true;
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
