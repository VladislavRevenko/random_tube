<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VotesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="votes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'video_id') ?>

    <?= $form->field($model, 'ip') ?>

    <?= $form->field($model, 'useragent') ?>

    <?= $form->field($model, 'vote') ?>

    <?php // echo $form->field($model, 'created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
