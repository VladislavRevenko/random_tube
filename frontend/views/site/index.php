<?php

/* @var $this yii\web\View */

$this->title = 'RandomTube';
?>
<div class="tile is-ancestor">
    <div class="tile is-parent is-10 center-block">
        <?php
        if (is_object($video)) {
            if ($video->link_video) { ?>
                <iframe id="ytplayer" type="text/html" width="100%" height="450px"
                        src="https://www.youtube.com/embed/<?= $video->link_video ?>"
                        data-video-id="<?= $video->link_video ?>"
                        data-video-cat="<? if ($_GET['cat']) {
                            echo $_GET['cat'];
                        } ?>" frameborder="0" allowfullscreen></iframe>
                <?php
            }
        } else {
            echo '<div class="center-block"><h3>Видео временно отсутствуют</h3></div>';
        }
        ?>

    </div>
</div>
<div class="tile is-ancestor">
    <div class="tile is-parent is-10 center-block level is-mobile">
        <a href="javascript:void(0)" class="button is-large level-left buttonIndex" id="like" name="buttonLike"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
        <input type="submit" class="button is-large buttonIndex" value="Ещё" id="get_video" name="buttonGetVideo">
        <a href="javascript:void(0)" class="button is-large level-right buttonIndex" id="dislike" name="buttonDislike"> <i class="fas fa-thumbs-down"></i></a>
    </div>
</div>


