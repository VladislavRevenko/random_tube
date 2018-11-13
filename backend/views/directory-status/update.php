<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DirectoryStatus */

$this->title = 'Обновить статус: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Справочник статусов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="directory-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
