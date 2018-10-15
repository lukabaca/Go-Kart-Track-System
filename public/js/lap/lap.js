$(document).ready(function (e) {
    let recordTable = $('#recordTable');
    // recordTable.DataTable();

    let defaultRecordLimit = 10;

    initRecords(recordTable, defaultRecordLimit);

    $('#allTimeRecord').on('click', function (e) {
       e.preventDefault();
       console.log('rekord wszech czasow');
    });

    $('#monthRecord').on('click', function (e) {
        e.preventDefault();
        console.log('rekord miesiaca');
    });

    $('#weekRecord').on('click', function (e) {
        e.preventDefault();
        console.log('rekord tygodnia');
    });

});

function initRecords(table, recordLimit) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/laps/loadRecords/' + recordLimit,
        success: function (data) {
            // console.log(data[0].time);

            for(let i = 0; i < data.length; i++) {
                let id = data[i].id;
                let time = data[i].time;
                let averageSpeed = data[i].averageSpeed;
                let date = data[i].date;

                let recordContent =
                    '<tr class="record-row" record-id='+id+'>' +
                        '<td class="record-info-td">' + id + '</td>' +
                        '<td class="record-info-td">' + time + '</td>' +
                        '<td class="record-info-td">' + averageSpeed + '</td>' +
                        '<td class="record-info-td">' + date + '</td>' +
                    '</tr>';

                table.find('tbody').append(recordContent);
            }
            // let recordingCol = $('[recording-id-area=' + recordingId + ']');
            // recordingCol.remove();

        },
        error: function (xhr, ajaxOptions, thrownError) {
            let statusCode = xhr.status;
            console.log(statusCode);


            switch (statusCode) {
                default : {
                    // let alertErrorContent =
                    //     '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    //     '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    //     '<span aria-hidden="true">X</span>' +
                    //     '</button>' +
                    //     '<strong>Wystąpił błąd podczas usuwania nagrania</strong>' +
                    //     '</div>';
                    //
                    // $('.alertArea').append(alertErrorContent);
                    // // $('.alert-danger').css('display', 'block');
                    break;
                }
            }
            // $('#formAddRecording')[0].reset();
        }
    });
}