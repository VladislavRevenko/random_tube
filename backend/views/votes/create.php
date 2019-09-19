<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Votes */

$this->title = 'Создание голоса';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="votes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
