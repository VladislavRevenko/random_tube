<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
    <?php $this->head() ?>

    <script>
        function deleteRecords() {
            var deletions = [];
            jQuery('tr td input:checkbox:checked').each(function (index, value) {
                if (jQuery(value).val().length > 0) {
                    deletions.push(jQuery(value).val());
                }
            });
            jQuery.post("<?=Yii::$app->urlManager->createUrl(['video/deletions'])?>", {'deletions[]': deletions})
                .done(function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.success == true) {
                        location.reload();
                    } else {
                        alert('При удалении произошла ошибка');
                    }
                });
            return false;
        }

        function openRecords() {
            //Not working in Chrome
            jQuery('.update-records').reverse().each(function () {
                setTimeout(() => {
                    window.open($(this).attr('href'), '_blank');
                }, 500);
            });
            return false;
        }

    window.onload = function() {
        jQuery.fn.reverse = [].reverse;

        jQuery('.to-queue').on('click', function () {
            var $form = jQuery(this).closest('form');
            if ($form.length > 0) {
                $form.find('.queue').val(1);
                $form.submit();
            }
            return false;
        });
    };
    </script>

</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'RandomTube',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index'],],
        ['label' => 'Видео', 'url' => ['/video/index'],],
        ['label' => 'Справочники', 'items' => [
            ['label' => 'Статусы', 'url' => ['/directory-status/index']]
        ]]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; RandomTube <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
