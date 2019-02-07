<?php




use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <?php

    try {
        return $this->render(Yii::getAlias('/page-templates/default/error.twig'));
    } catch (\yii\base\ViewNotFoundException $e) {
        return $this->render(Yii::getAlias('/page-templates/default/error.twig'));
    }
    ?>

</div>
