$(document).ready(function () {
    var count = $('.add-video-block').length;
    deleteLinkVideo();
    $('#addInputLink').on('click', function () {
        if (count < 10) {
            $(this).parent().parent().before('<div class="tile is-ancestor add-video-block"> <div class="tile is-parent deleteBlock has-addons is-8 center-block">' +
                '<input type="text" name="name_video[' + count + ']" class="input is-medium name_video" placeholder="Название видео">' +
                '<input type="text" name="link_video[' + count + ']" class="input is-medium link_video" placeholder="Youtube url">' +
                '<a href="javascript:void(0)" id="delete" class="button is-medium deleteLink"><i class="fas fa-trash"></i></a>' +
                '</div></div>');
            deleteLinkVideo();
            count++;
        } else {
            $('#result').html('<h4>Вы создали максимальное количество ссылок</h4>');
        }
    });

    $('#submitForm').on('click', function () {
        ajaxFormSend('ajaxForm', '/site/add-send/');
        return false;
    });

    $('.buttonIndex').on('click', function () {
        var idButton = $(this).attr('id');
        var srcVideo = $('iframe').attr('data-video-id');

        if (idButton == 'get_video') {
            if (srcVideo.length > 0) {
                ajaxGetVideo(srcVideo, '/site/get-video/');
            }
        }
        else if ((idButton == 'like') || (idButton == 'dislike')) {
            ajaxRating(idButton, srcVideo, '/site/ratings/');
        } else {
            alert('Что то пошло не так. Попробуйте позже');
        }
        return false;
    });
});

function deleteLinkVideo() {
    $('.deleteLink').on('click', function () {
        $(this).parent().remove();
    });
}

function ajaxFormSend(ajaxForm, url) {
    var dataForm = $('#' + ajaxForm).serialize();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: dataForm,
        success: function (response) {
            if (response.message == 'Заявка отправленна') {
                $('#result').html(response.message);
                setTimeout(function () {
                    location.reload();
                }, 6000);
            } else {
                $('#result').html(response.message);
                setTimeout(function () {
                    $('#result').html('');
                }, 3000);
            }
        },
        error: function (response) {
            $('#result').html(response.message);
        }
    });
}

function ajaxGetVideo(srcVideo, url) {
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            'srcVideo': srcVideo,
        },
        success: function (response) {
            $('iframe').attr('src', 'https://www.youtube.com/embed/' + response.newSrc);
            $('iframe').attr('data-video-id', response.newSrc);
        },
        error: function (response) {
        }
    });
}

function ajaxRating(idButton, srcVideo, url) {
    var date = new Date();
    var dateParse = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            'button': idButton,
            'date': dateParse,
            'video': srcVideo,
        },
        success: function (response) {
            if (response.success == false) {
                alert(response.message);
            }
        },
        error: function (response) {
        }
    })
    ;
}
