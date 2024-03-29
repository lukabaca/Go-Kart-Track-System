$(document).ready(function () {
    let responseElement = $('.responseInfo');
    let openModalButton = $('#addRecording');
    let addRecordButton = $('#recording_submit');
    let inputLink = $('#recording_recordingLink');
    let linkErrorInfo = $('#linkError');
    let inputTitle = $('#recording_title');
    let titleErrorInfo = $('#titleError');
    let ytLinkRegex = '/((http://)?)(www\.)?((youtube\.com/)|(youtu.be)|(youtube)).+';
    let maxTitleLenght = 45;
    let isValidTitle = true;
    let isValidLink = true;

    openModalButton.on('click', function (e) {
       e.preventDefault();
       stopLoadingProgress();
    });

    inputLink.on('change', function (e) {
       e.preventDefault();
        if(!$(this).val().length) {
            linkErrorInfo.text('');
            isValidLink = true;
        } else {
            if(validateString($(this).val(), ytLinkRegex)) {
                linkErrorInfo.text('');
                isValidLink = true;
            } else {
                isValidLink = false;
                linkErrorInfo.text('Brak poprawnego formatu linku YT');
            }
        }
        checkButtonStatus();
    });

    inputTitle.on('change', function (e) {
        e.preventDefault();
        let titleLength = $(this).val().length;
        if(titleLength > maxTitleLenght) {
            titleErrorInfo.text('Maksymalna długość tytułu to 45 znaków');
            isValidTitle = false;
        } else {
            isValidTitle = true;
            titleErrorInfo.text('');
        }
        checkButtonStatus();
    });

    $('body').on('click', '.deleteRecordingIcon', function (e) {
        e.stopPropagation();
        let recordingId = $(this).closest('.col').attr('recording-id');
        deleteRecording(recordingId);
    });

    $('#formAddRecording').submit(function (event) {
        event.preventDefault();
        let title = $('#recording_title').val();
        let link = $('#recording_recordingLink').val();
        addRecording(title, link);
    });

    function validateString(link, regex) {
        let isMatching = link.match(regex);
        return isMatching;
    }

    function deleteRecording(recordingId) {
        $('.loader').css('display', 'block');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/recording/deleteRecording/' + recordingId,
            success: function (data) {
                let recordingCol = $('[recording-id-area='+recordingId+']');
                recordingCol.remove();
                stopLoadingProgress();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                switch (statusCode) {
                    default : {
                        errorAlert('Wystąpił błąd podczas usuwania nagrania');
                        break;
                    }
                }
                $('#formAddRecording')[0].reset();
                stopLoadingProgress();
            }
        });
    }

    function addRecording(title, link) {
        startLoadingProgress();
        title = title.trim();
        link = link.trim();
        let recordingData = {
            "title": title,
            "link": link,
        };
        recordingData = JSON.stringify(recordingData);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/recording/addRecording',
            data: {
                recordingData: recordingData
            },
            success: function (data) {
                let isValid = true;
                let recordingLink = (data.link === null || data.link === undefined) ? isValid = false : data.link;
                let recordingTitle = (data.title === null || data.title === undefined) ? '' : data.title;
                let recordingId = (data.id === null || data.id === undefined) ? isValid = false : data.id;
                responseElement.text('');
                $('#formAddRecording')[0].reset();
                if(isValid) {
                    let content =
                        '<div class="col-md-4 recordingCol" recording-id-area=' + recordingId + '>' +
                        '<div class="shadow p-3 mb-5 bg-white rounded card videoCard">' +
                        '<div class="row">' +
                        '<div class="col" recording-id=' + recordingId + '>' +
                        '<span class="card-title">' + recordingTitle + '</span>' +
                        '<i class="fa fa-trash deleteRecordingIcon float-right" aria-hidden="true">' + '</i>' +
                        '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<iframe class="video" src="' + recordingLink + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>' + '</iframe>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    $('.recordingArea').append(content);
                    $('#addRecordingModal').modal('hide');
                    successAlert('Dodano nagranie');
                } else {

                }
                stopLoadingProgress();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                switch (statusCode) {
                    default : {
                        responseElement.text('Wystąpił błąd po stronie serwera, spróbuj ponownie');
                        break;
                    }
                }
                $('#formAddRecording')[0].reset();
               stopLoadingProgress();
            }
        });
    }

    function checkButtonStatus() {
        if(isValidTitle && isValidLink) {
            addRecordButton.removeAttr("disabled");
        } else {
            addRecordButton.attr("disabled", "disabled");
        }
    }
});

function successAlert(message) {
    let alertSuccessContent =
        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">X</span>' +
        '</button>' +
        '<strong>'+message+'</strong>' +
        '</div>';
    $('.alertArea').append(alertSuccessContent);
}
function errorAlert(message) {
    let alertErrorContent =
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">X</span>' +
        '</button>' +
        '<strong>'+message+'</strong>' +
        '</div>';
    $('.alertArea').append(alertErrorContent);
}
function startLoadingProgress() {
    $('.loader').css('display', 'block');
}
function stopLoadingProgress() {
    $('.loader').css('display', 'none');
}
