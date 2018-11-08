<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Video;


/* @var $this yii\web\View */
/* @var $model common\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link_video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Video::getDropDownStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
