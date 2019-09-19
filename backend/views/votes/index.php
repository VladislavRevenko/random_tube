<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Голосование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="votes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать голос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'video_id',
            'ip',
            'useragent',
            'vote',
            'created',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
