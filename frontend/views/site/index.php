<?php

/* @var $this yii\web\View */

$this->title = 'RandomTube';
?>
<div class="tile is-ancestor">
    <div class="tile is-parent is-10 center-block">
        <?php
        if (is_object($video)) {
            if ($video->link_video) { ?>
<div id="ytplayer"></div>
<script>
  // Load the IFrame Player API code asynchronously.
  var video_id = '<?= $video->link_video?>';
  window.onload = function() {
      createPlayer();
  };

</script>
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
        <input type="submit" class="button is-large buttonIndex center-block" value="Ещё" id="get_video" name="buttonGetVideo">
        <a href="javascript:void(0)" class="button is-large level-right buttonIndex" id="dislike" name="buttonDislike"> <i class="fas fa-thumbs-down"></i></a>
    </div>
</div>


