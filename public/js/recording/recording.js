$(document).ready(function () {
    let responseElement = $('.responseInfo');
    let openModalButton = $('#addRecording');

    let addRecordButton = $('#recording_submit');
    let inputLink = $('#recording_recordingLink');
    let linkErrorInfo = $('#linkError');

    let inputTitle = $('#recording_title');
    let titleErrorInfo = $('#titleError');

    let deleteRecordingIcon = $('.deleteRecordingIcon');


    let isDisabled = true;

    let ytLinkRegex = '/((http://)?)(www\.)?((youtube\.com/)|(youtu.be)|(youtube)).+';
    let maxTitleLenght = 45;

    let isValidTitle = true;
    let isValidLink = true;

    openModalButton.on('click', function (e) {
       e.preventDefault();
        $('.alert').css('display', 'none');
    });

    inputLink.on('change', function (e) {
       e.preventDefault();

        if($(this).val().length == 0) {
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

        if(isValidTitle && isValidLink) {
            addRecordButton.removeAttr("disabled");
        } else {
            addRecordButton.attr("disabled", "disabled");
        }
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

        if(isValidTitle && isValidLink) {
            addRecordButton.removeAttr("disabled");
        } else {
            addRecordButton.attr("disabled", "disabled");
        }
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
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/recording/deleteRecording/' + recordingId,
                success: function (data) {

                    let recordingCol = $('[recording-id-area='+recordingId+']');
                    recordingCol.remove();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    let statusCode = xhr.status;

                    switch (statusCode) {
                        default : {
                            let alertErrorContent =
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">X</span>' +
                                '</button>' +
                                '<strong>Wystąpił błąd podczas usuwania nagrania</strong>' +
                                '</div>';

                            $('.alertArea').append(alertErrorContent);
                            // $('.alert-danger').css('display', 'block');
                            break;
                        }
                    }
                    $('#formAddRecording')[0].reset();
                }
            });
        }
        function addRecording(title, link) {

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
                    let recordingLink = (data.link === null || data.link === undefined) ? isValid = false : data.title;
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

                        let alertSuccessContent =
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">X</span>' +
                            '</button>' +
                            '<strong>Dodano nagranie</strong>' +
                            '</div>';

                        $('.alertArea').append(alertSuccessContent);
                        // $('.alert-success').css('display', 'block');
                    } else {

                    }
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
                }
            });
        }
});