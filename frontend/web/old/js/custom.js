$(document).ready(function () {
    var count = 1;
    deleteLinkVideo();
    $('#addInputLink').on('click', function () {
        if (count < 10) {
            $(this).parent().parent().before('<div class="tile is-ancestor"> <div class="tile is-parent deleteBlock has-addons is-8 center-block">' +
                '<input type="text" name="name_video[' + count + ']" class="input is-medium name_video" placeholder="Название видео">' +
                '<input type="text" name="linkVideo[' + count + ']" class="input is-medium link_video" placeholder="Youtube url">' +
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
        var srcVideo = $('iframe').attr('src');
        ajaxIndex(idButton, srcVideo, '/site/button-video/');
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

function ajaxIndex(button, srcVideo, url) {
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            'idButton': button,
            'srcVideo': srcVideo,
        },
        success: function (response) {
            $('iframe').attr('src', response.newSrc);
        },
        error: function (response) {
        }
    });
}
