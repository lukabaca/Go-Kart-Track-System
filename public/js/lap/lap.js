$(document).ready(function (e) {
    let recordTable = $('#recordTable');
    let recordNumber = $('#recordNumber');
    let recordNumberHeader = $('#recordNumberHeader');
    let timeModeDictionary = {'allTime' : 1, 'month' : 2, 'week' : 3};
    let defaultRecordLimit = 10;

    recordNumber.text(defaultRecordLimit);
    loadRecords(recordTable, defaultRecordLimit, timeModeDictionary['allTime']);

    $('#limitRecordSelect').on('change', function (e) {
       e.preventDefault();
       let recordLimit = $('#limitRecordSelect option:selected').val();
       recordNumber.text(recordLimit);
       clearTable(recordTable);
       loadRecords(recordTable, recordLimit, timeModeDictionary['allTime']);
    });


    $('#allTimeRecord').on('click', function (e) {
       e.preventDefault();
       let recordLimit = $('#limitRecordSelect option:selected').val();
       clearTable(recordTable);
       loadRecords(recordTable, recordLimit, timeModeDictionary['allTime']);
    });

    $('#monthRecord').on('click', function (e) {
        e.preventDefault();
        let recordLimit = $('#limitRecordSelect option:selected').val();
        clearTable(recordTable);
        loadRecords(recordTable, recordLimit, timeModeDictionary['month']);
    });

    $('#weekRecord').on('click', function (e) {
        e.preventDefault();
        let recordLimit = $('#limitRecordSelect option:selected').val();
        clearTable(recordTable);
        loadRecords(recordTable, recordLimit, timeModeDictionary['week']);
    });

});

function loadRecords(table, recordLimit, timeMode) {
    $('.loader').css('display', 'block');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/laps/loadRecords/' + recordLimit + '/' + timeMode,
        success: function (data) {
            // console.log(data[0].time);

            for(let i = 0; i < data.length; i++) {
                let position = i + 1;
                let id = data[i].id;
                let time = data[i].time;
                let averageSpeed = data[i].averageSpeed;
                let date = data[i].date;

                let recordContent =
                    '<tr class="record-row" record-id='+id+'>' +
                        '<td class="record-info-td">' + position + '</td>' +
                        '<td class="record-info-td">' + id + '</td>' +
                        '<td class="record-info-td">' + time + '</td>' +
                        '<td class="record-info-td">' + averageSpeed + '</td>' +
                        '<td class="record-info-td">' + date + '</td>' +
                    '</tr>';

                table.find('tbody').append(recordContent);
            }
            // let recordingCol = $('[recording-id-area=' + recordingId + ']');
            // recordingCol.remove();
            $('.loader').css('display', 'none');
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
            $('.loader').css('display', 'none');
            // $('#formAddRecording')[0].reset();
        }
    });
}

function clearTable(table) {
    table.find('tbody tr').each(function () {
        $(this).remove();
    });
}