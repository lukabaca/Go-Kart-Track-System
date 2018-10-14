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
        // $(".alert").alert('close');
        console.log('ss');
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

    deleteRecordingIcon.on('click', function (e) {
       e.preventDefault();
       let recordingId = $('.recordingCol').attr('recording-id');
       console.log(recordingId);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/recording/deleteRecording/' + recordingId,
            success: function (data) {
                console.log(data);

                // let recordingCol = $('.recordingCol').attr('recording-id');
                let recordingCol = $('[recording-id='+recordingId+']');
                console.log(recordingCol);
                recordingCol.remove();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                console.log(statusCode);

                switch (statusCode) {
                    default : {
                        responseElement.text('Wystąpił błąd po stronie serwera, spróbuj ponownie');
                        break;
                    }
                }
                $('#formAddRecording')[0].reset();
            }
        });
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
                    // let title = (reservations[i].title === null || reservations[i].title === undefined) ? (isValid = false) : reservations[i].title;
                    let recordingLink = data.link;
                    let recordingTitle = data.title;

                    responseElement.text('');
                    $('#formAddRecording')[0].reset();

                    let content =
                        '<div class="col-md-4">' +
                            '<div class="shadow p-3 mb-5 bg-white rounded card videoCard">' +
                        '<div class="row">' +
                            '<div class="col">' +
                                '<span class="card-title">'+recordingTitle+'</span>' +
                                '<i class="fa fa-trash deleteRecordingIcon float-right" aria-hidden="true">'+'</i>' +
                            '</div>' +
                        '</div>' +
                                '<div class="card-body">' +
                                '<iframe class="video" src="'+recordingLink+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>'+'</iframe>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

                    $('.recordingArea').append(content);

                    $('#addRecordingModal').modal('hide');
                    $('.alert').css('display', 'block');
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