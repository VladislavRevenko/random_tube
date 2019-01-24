<form method="POST" id="ajaxForm">
    <?php if (!empty($category_id)):?>
    <input type="hidden" name="category_id" value="<?=$category_id?>">
    <?php endif;?>
    <div class="tile is-ancestor add-video-block">
        <div class="tile is-parent deleteBlock has-addons is-8 center-block">
            <input type="text" name="name_video[]" class="input is-medium name_video is-3" placeholder="Название видео">
            <input type="text" name="link_video[]" class="input is-medium link_video is-3" placeholder="Youtube url">
            <a href="javascript:void(0)" id="delete" class="button is-medium deleteLink"><i class="fas fa-trash"></i></a>
        </div>
    </div>
    <div class="tile is-ancestor">
        <div class="tile is-parent is-8 center-block">
            <input type="button" class="button is-medium center-block" id="addInputLink" value="Еще">
        </div>
    </div>
    <div class="tile is-ancestor">
        <div class="tile is-parent is-8 center-block">
            <div class="center-block" id="result"></div>
        </div>
    </div>
    <div class="tile is-ancestor">
        <div class="tile is-parent is-8 center-block">
            <input type="submit" class="button is-medium center-block" id="submitForm" value="Отправить">
        </div>
    </div>
</form>
