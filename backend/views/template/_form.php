<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Form */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'layout_twig')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <?= $form->field($model, 'index_twig')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <?= $form->field($model, 'error_twig')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <?= $form->field($model, 'add_twig')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <?= $form->field($model, 'categories_twig')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <?= $form->field($model, 'style_css')->widget(
        'trntv\aceeditor\AceEditor',
        [
            'mode'=>'twig', // programing language mode. Default "html"
            'theme'=>'github', // editor theme. Default "github"
            'readOnly'=>'false' // Read-only mode on/off = true/false. Default "false"
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
