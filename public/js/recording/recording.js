$(document).ready(function () {

    let addRecordButton = $('#recording_submit');
    let inputLink = $('#recording_recordingLink');
    let isDisabled = true;

    let ytLinkRegex = '/((http://)?)(www\.)?((youtube\.com/)|(youtu.be)|(youtube)).+';

    addRecordButton.addClass('disabled');

    inputLink.on('change', function (e) {
       e.preventDefault();
       console.log($(this).val());

       if(validateYTlink($(this).val(), ytLinkRegex)) {
           addRecordButton.removeClass('disabled');
       } else {
           addRecordButton.addClass('disabled');
       }
    });

    $('#formAddRecording').submit(function (event) {
        event.preventDefault();
        console.log('z submitowano');

        let title = $('#recording_title').val();
        let link = $('#recording_recordingLink').val();

        console.log(title);
        console.log(link);

        addRecording(title, link);
    });
        function validateYTlink(link, regex) {
            let isMatching = link.match(regex);
            return isMatching;
        }


        function addRecording(title, link) {

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