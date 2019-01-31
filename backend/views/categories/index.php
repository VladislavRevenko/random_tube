<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'code',
            'template_id',
//            [
//                    'attribute' => 'template',
//                    'value' => function ($dataProvider) {
//                        return $dataProvider->template->code;
//                    }
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
