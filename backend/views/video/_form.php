<?php

use common\models\Categories;
use common\models\DirectoryStatus;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'link_video')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(DirectoryStatus::find()->select(['id', 'status'])->asArray()->all(), 'id', 'status')); ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Categories::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name'),
        [
            'prompt' => 'Не задано',
            'options' => ['0' => ['select' => true]]
        ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
