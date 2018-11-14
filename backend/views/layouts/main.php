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
        window.onload = function() {

            jQuery('.delete-records').on('click', function() {
                var deletions = [];
                $('tr td input:checkbox:checked').each(function (index, value) {
                    if ($(value).val().length > 0) {
                        deletions.push($(value).val());
                    }
                });
                $.post("<?=Yii::$app->urlManager->createUrl(['video/deletions'])?>", {'deletions[]': deletions})
                    .done(function (data) {
                        data = $.parseJSON(data);
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert('При удалении произошла ошибка');
                        }
                    });
                return false;
            });

            jQuery('.activate-records').on('click', function() {
                var activeItems = [];
                $('tr td input:checkbox:checked').each(function (index, value) {
                    if ($(value).val().length > 0) {
                        activeItems.push($(value).val());
                    }
                });
                if (activeItems.length > 0) {
                    $.ajax({
                        url: '<?=Yii::$app->urlManager->createUrl(["video/activate"])?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'activateItems[]': activeItems
                        },
                        success: function (response) {
                            // response = $.parseJSON(response);
                            if (response.success == true) {
                                location.reload();
                                // console.log('success');
                                // console.log(response);
                            } else {
                                alert('При активации произошла ошибка');
                                console.log(response);
                            }
                        },
                        error: function (response) {
                            console.log('error');
                            console.log(response);
                        }
                    });

                } else {
                    alert('Вы не выбрали записи');
                }
                console.log(activeItems);
            });
        }
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
