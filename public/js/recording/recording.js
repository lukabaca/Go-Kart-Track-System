$(document).ready(function () {


    let responseElement = $('.responseInfo');

    let addRecordButton = $('#recording_submit');
    let inputLink = $('#recording_recordingLink');
    let linkErrorInfo = $('#linkError');

    let inputTitle = $('#recording_title');
    let titleErrorInfo = $('#titleError');

    let isDisabled = true;

    let ytLinkRegex = '/((http://)?)(www\.)?((youtube\.com/)|(youtu.be)|(youtube)).+';
    let maxTitleLenght = 45;

    let isValidTitle = true;
    let isValidLink = true;

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
            console.log(recordingData);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/recording/addRecording',
                data: {
                    recordingData: recordingData
                },
                success: function (data) {
                    console.log(data);
                    responseElement.text('');
                    $('#formAddRecording')[0].reset();
                    $('#addRecordingModal').modal('hide');

                    $('.alert').css('display', 'block');
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
        }
});