$(document).ready(function () {

    let addRecordButton = $('#recording_submit');
    let inputLink = $('#recording_recordingLink');
    let linkErrorInfo = $('#linkError');

    let inputTitle = $('#recording_title');
    let titleErrorInfo = $('#titleError');

    let isDisabled = true;

    let ytLinkRegex = '/((http://)?)(www\.)?((youtube\.com/)|(youtu.be)|(youtube)).+';
    let maxTitleLenght = 4;
    // let titleRegex = '/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ][0-9]+$/';

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
                // addRecordButton.removeAttr("disabled");
            } else {
                isValidLink = false;
                linkErrorInfo.text('Brak poprawnego formatu linku YT');
            }
        }

       // if(validateString($(this).val(), ytLinkRegex)) {
       //     linkErrorInfo.text('');
       //     isValidLink = true;
       //     // addRecordButton.removeAttr("disabled");
       // } else {
       //
       //     if($(this).val().length == 0) {
       //         linkErrorInfo.text('');
       //         isValidLink = true;
       //         // addRecordButton.removeAttr("disabled");
       //     } else {
       //         // addRecordButton.attr("disabled", "disabled");
       //         isValidLink = false;
       //         linkErrorInfo.text('Brak poprawnego formatu linku YT');
       //     }
       // }
       console.log('link ' + isValidLink);
       console.log('title ' + isValidTitle);

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
            // addRecordButton.attr( "disabled", "disabled" );
        } else {
            isValidTitle = true;
            titleErrorInfo.text('');
            // addRecordButton.removeAttr("disabled");
        }
        console.log('link ' + isValidLink);
        console.log('title ' + isValidTitle);

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
                    $('#formAddRecording')[0].reset();
                    // // $('#modalCorrectReservation').modal('open');
                    // $('#makeReservation')[0].reset();
                    //
                    //
                    // let reservationID = data.id;
                    // let reservationDate = data.startTime;
                    //
                    // (reservationID === null || reservationID === undefined) ? (window.location.href = '/APIerror') : ('');
                    // // (reservationID === null ||reservationID === undefined) ? (window.location.href = '/APIerror') : ('');
                    //
                    //
                    // window.location.href = 'meeting/detailsReservation/' + reservationID;
                    //
                    // // window.location.href = '/meeting';
                    // // getReservations(reservationID, reservationDate);
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    let statusCode = xhr.status;
                    console.log(statusCode);
                    //
                    // let responseElement = $('.responseInfo');
                    //
                    // switch (statusCode) {
                    //     case 400: {
                    //
                    //         responseElement.text('Nie można sparsować przesłanych dat');
                    //         break;
                    //     }
                    //     case 404: {
                    //
                    //         responseElement.text('Podano złe parametry');
                    //         break;
                    //     }
                    //     case 409: {
                    //
                    //         responseElement.text('Ten termin jest już zajęty');
                    //         break;
                    //     }
                    //     default : {
                    //         window.location.href = '/APIerror';
                    //         break;
                    //     }
                    // }
                    // $('#modalReservationResponseInfo').modal('open');

                }
            });
        }
});