<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="section">
    <div class="container">
        <div class="tile is-ancestor">
            <div class="tile is-parent is-10 center-block">
                <iframe id="ytplayer" type="text/html" width="100%" height="450px"
                        src="<?= $video->link_video ?>"
                        frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
        <div class="tile is-ancestor">
            <div class="tile is-parent is-10 center-block level is-mobile">
                <a href="javascript:void(0)" class="button is-large level-left buttonIndex" id="like" name="buttonLike"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                <input type="submit" class="button is-large buttonIndex" value="Ещё" id="still" name="buttonStill">
                <a href="javascript:void(0)" class="button is-large level-right buttonIndex" id="dislike" name="buttonDislike"> <i class="fas fa-thumbs-down"></i></a>
            </div>
        </div>
    </div>
</div>


