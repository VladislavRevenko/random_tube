<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Votes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="votes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'video_id')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'useragent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vote')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
