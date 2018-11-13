<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DirectoryStatus */

$this->title = 'Справочник статусов';
$this->params['breadcrumbs'][] = ['label' => 'Directory Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="directory-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
