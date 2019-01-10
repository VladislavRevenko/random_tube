<?php

use common\models\Categories;
use yii\helpers\Url;

$categories = Categories::find()->asArray()->all();
if (count($categories) != 0) {
    foreach ($categories as $category) { ?>
        <div class="tile is-ancestor">
            <div class="tile is-parent">
                <div class="center-block">
                    <a href="<?= Url::toRoute('/' . $category['code']); ?>" class="btn btn-default itemCategoryMenu"><h4><?= $category['name'] ?></h4></a><br>
                </div>
            </div>
        </div>
    <?php }
} else {
    echo "<h1>Категории временно отсутствуют</h1>";
}
?>