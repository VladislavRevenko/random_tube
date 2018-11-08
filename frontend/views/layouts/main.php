<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="/old/css/site.css">
    <link rel="stylesheet" href="/css/bulma.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
          crossorigin="anonymous">

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php /*
    NavBar::begin([
            'brandLabel' => 'RandomTube',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'nav navbar-fixed-top navbar-inverse',
            ],
        ]);
        $menuItems = [
            ['label' => 'Add Video', 'url' => ['/site/add']],
            ['label' => 'Add Video', 'url' => ['/site/add']],
            ['label' => 'Add Video', 'url' => ['/site/add']],
        ];

        echo Nav::widget([
            'options' => ['class' => ' nav navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        */ ?>

    <div class="container">
        <a href="<?= Yii::$app->homeUrl ?>" class="pull-left"><h4>RandomTube</h4></a>
        <a href="/site/add" class="btn pull-right"><h4>Добавить видео</h4></a>
    </div>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">

    <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="/old/js/custom.js"></script>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
