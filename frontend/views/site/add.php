<form method="POST" id="ajaxForm">
    <div class="section">
        <div class="container">
            <div class="tile is-ancestor">
                <div class="tile is-parent deleteBlock has-addons field is-6 center-block">
                    <input type="text" name="linkVideo[0]" class="input is-medium link_video" placeholder="Youtube url">
                    <a href="javascript:void(0)" id="delete" class="button is-medium deleteLink"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <div class="tile is-ancestor">
                <div class="tile is-parent">
                    <input type="button" class="button is-medium center-block" id="addInputLink" value="Еще">
                </div>
            </div>
            <div class="tile is-ancestor">
                <div class="tile is-parent">
                    <div class="center-block" id="result"></div>
                </div>
            </div>
            <div class="tile is-ancestor">
                <div class="tile is-parent">
                    <input type="submit" class="button is-medium center-block" id="submitForm" value="Отправить">
                </div>
            </div>
        </div>
    </div>
</form>
