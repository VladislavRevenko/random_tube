<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Видео';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать видео', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            'name',
            'link_video',
            [
                'attribute' => 'status_id',
                'value' => function ($dataProvider) {
                    if (is_object($dataProvider->directoryStatus)) {
                        return $dataProvider->directoryStatus->status;
                    }
                    return 'Не задано';
                }
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($dataProvider) {
                    if (is_object($dataProvider->categories)) {
                        return $dataProvider->categories->name;
                    }
                    return 'Не задано';
                }
            ],
            'rating',
            'views',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <button class="btn btn-danger delete-records">Удалить</button>
    <button class="btn btn-success activate-records">Активировать записи</button>
</div>
