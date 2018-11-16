<?php

namespace console\controllers;


use common\models\Video;
use common\models\Votes;

class RatingController extends \yii\console\Controller
{
    public function actionUpdate()
    {
        $video_list = Video::find()->where(['status_id' => 1])->all();
        if (!empty($video_list)) {
            foreach ($video_list as $video) {
                $voteResult = 0;
                if (!empty($video->id)) {
                    $votes = Votes::find()->where(['video_id' => $video->id])->select('vote')->all();
                    if (!empty($votes)) {
                        foreach ($votes as $vote) {
                            $voteResult = $voteResult + $vote->vote;
                        }
                    }
                    $video_up = Video::findOne($video->id);
                    if (is_object($video_up)) {
                        $video_up->rating = $voteResult;
                        $video_up->save();
                    }
                }
            }
        }
    }
}