var player;

function createPlayer() {
    'use strict';
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.ENDED) {
        loadVideo();
    }
}

// Replace the 'ytplayer' element with an <iframe> and
// YouTube player after the API code downloads.
function onYouTubePlayerAPIReady() {
    'use strict';
    player = new YT.Player('ytplayer', {
        height: '450',
        width: '100%',
        videoId: window.video_id,
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

function loadVideo() {
    jQuery.ajax({
        url: location.href,
        type: 'POST',
        data: {
            'video_id': window.video_id,
        },
        success: function (response) {
            player.loadVideoById(response);
            window.video_id = response;
        },
        error: function (response) {
            console.log(response);
        }
    });
}

function deleteLinkVideo() {
    jQuery('.deleteLink').on('click', function () {
        jQuery(this).parent().remove();
    });
}

function ajaxFormSend(category, ajaxForm, url) {
    var dataForm = jQuery('#' + ajaxForm).serializeArray();
    dataForm.push({name: "category", value: category});
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: dataForm,
        success: function (response) {
            if (response.message == 'Заявка отправленна') {
                setTimeout(function () {
                    jQuery('#result').html(response.message);
                    location.reload();
                }, 6000);
            } else {
                jQuery('#result').html(response.message);
                setTimeout(function () {
                    jQuery('#result').html('');
                }, 10000);
            }
        },
        error: function (response) {
            jQuery('#result').html(response.message);
        }
    })
    ;
}

function ajaxRating(idButton, srcVideo, url) {
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            'button': idButton,
            'video': srcVideo,
        },
        success: function (response) {
            if (response.success == true) {
                if (idButton == 'like') {
                    jQuery('a#dislike').hide();
                    jQuery('#like').removeClass('fa fa-thumbs-up');
                    jQuery('#like').text('Ваш голос учтен');
                    setTimeout(function (){
                        jQuery('a#dislike').show();
                        jQuery('#like').addClass('fa fa-thumbs-up');
                        jQuery('#like').text('');
                    }, 1000);
                } else if (idButton == 'dislike') {
                    jQuery('a#like').hide();
                    jQuery('#dislike').removeClass('fa fa-thumbs-down');
                    jQuery('#dislike').text('Ваш голос учтен');
                    setTimeout(function (){
                        jQuery('a#dislike').show();
                        jQuery('#like').addClass('fa fa-thumbs-down');
                        jQuery('#like').text('');
                    }, 1000);
                }
            } else if (response.success == false) {
                alert(response.message);
            }
        },
        error: function (response) {
        }
    })
    ;
}

jQuery(document).ready(function() {
    'use strict';
    jQuery('#get_video').on('click', function() {
        loadVideo();
    });

    var count = jQuery('.add-video-block').length;
    deleteLinkVideo();
    jQuery('#addInputLink').on('click', function () {
        if (count < 10) {
            jQuery(this).parent().parent().before('<div class="tile is-ancestor add-video-block"> <div class="tile is-parent deleteBlock has-addons is-8 center-block">' +
                '<input type="text" name="name_video[' + count + ']" class="input is-medium name_video" placeholder="Название видео">' +
                '<input type="text" name="link_video[' + count + ']" class="input is-medium link_video" placeholder="Youtube url">' +
                '<a href="javascript:void(0)" id="delete" class="button is-medium deleteLink"><i class="fas fa-trash"></i></a>' +
                '</div></div>');
            deleteLinkVideo();
            count++;
        } else {
            jQuery('#result').html('<h4>Вы создали максимальное количество ссылок</h4>');
        }
    });

    jQuery('#submitForm').on('click', function () {
        var category = jQuery('div.category').attr('name');
        ajaxFormSend(category, 'ajaxForm', '/site/add-send/');
        $('#ajaxForm')[0].reset();
        return false;
    });

    jQuery('.buttonIndex').on('click', function () {
        var idButton = jQuery(this).attr('id');
        if ((idButton == 'like') || (idButton == 'dislike')) {
            ajaxRating(idButton, window.video_id, '/site/ratings/');
        }
        return false;
    });
});