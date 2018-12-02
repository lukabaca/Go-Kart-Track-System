$(document).ready(function (e) {
    let recordTable = $('#recordTable');
    let recordNumber = $('#recordNumber');
    let recordNumberHeader = $('#recordNumberHeader');
    let timeModeDictionary = {'allTimeRecord' : 1, 'monthRecord' : 2, 'weekRecord' : 3};
    let defaultRecordLimit = 10;

    recordNumber.text(defaultRecordLimit);
    loadRecords(recordTable, defaultRecordLimit, timeModeDictionary['allTimeRecord']);

    $('#limitRecordSelect').on('change', function (e) {
       e.preventDefault();
       let recordLimit = $('#limitRecordSelect option:selected').val();
       recordNumber.text(recordLimit);
       clearTable(recordTable);
       let actualTimeModeSelected = $("ul#myTab li a.active").attr('id');
       loadRecords(recordTable, recordLimit, timeModeDictionary[actualTimeModeSelected]);
    });

    $('#allTimeRecord').on('click', function (e) {
       e.preventDefault();
       removeAlertLabel('.alert');
       let recordLimit = $('#limitRecordSelect option:selected').val();
       clearTable(recordTable);
       loadRecords(recordTable, recordLimit, timeModeDictionary['allTimeRecord']);
    });

    $('#monthRecord').on('click', function (e) {
        e.preventDefault();
        removeAlertLabel('.alert');
        let recordLimit = $('#limitRecordSelect option:selected').val();
        clearTable(recordTable);
        loadRecords(recordTable, recordLimit, timeModeDictionary['monthRecord']);
    });

    $('#weekRecord').on('click', function (e) {
        e.preventDefault();
        removeAlertLabel('.alert');
        let recordLimit = $('#limitRecordSelect option:selected').val();
        clearTable(recordTable);
        loadRecords(recordTable, recordLimit, timeModeDictionary['weekRecord']);
    });
});

function loadRecords(table, recordLimit, timeMode) {
    $('.loader').css('display', 'block');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/laps/loadRecords/' + recordLimit + '/' + timeMode,
        success: function (data) {
            let isValid = true;
            for(let i = 0; i < data.length; i++) {
                let position = i + 1;
                let id = (data[i].id === null || data[i].id === undefined) ? isValid = false : data[i].id;
                let time = (data[i].time === null || data[i].time === undefined) ? isValid = false : data[i].time;
                let date = (data[i].date === null || data[i].date === undefined) ? isValid = false : data[i].date;

                let userName = (data[i].user.name === null || data[i].user.name === undefined) ? isValid = false : data[i].user.name;
                let userSurname = (data[i].user.surname === null || data[i].user.surname === undefined) ? isValid = false : data[i].user.surname;
                let kartName = (data[i].kart.name === null || data[i].kart.name === undefined) ? isValid = false : data[i].kart.name;

                if(isValid) {
                    let recordContent =
                        '<tr class="record-row" record-id=' + id + '>' +
                        '<td class="record-info-td">' + position + '</td>' +
                        '<td class="record-info-td">' + userName + ' ' + userSurname + '</td>' +
                        '<td class="record-info-td">' + time + '</td>' +
                        '<td class="record-info-td">' + kartName + '</td>' +
                        '<td class="record-info-td">' + date + '</td>' +
                        '</tr>';
                    table.find('tbody').append(recordContent);
                    isValid = true;
                }
            }
            $('.loader').css('display', 'none');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            removeAlertLabel('.alert');
            let statusCode = xhr.status;
            let errorMessage;
            switch (statusCode) {
                case 404: {
                    errorMessage = 'Nie znaleziono rekordów dla tego kryterium';
                    break;
                }
                default : {
                    errorMessage = 'Wystąpił błąd podczas pobierania danych';
                    break;
                }
            }
            let alertErrorContent =
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">X</span>' +
                '</button>' +
                '<strong>'+errorMessage+'</strong>' +
                '</div>';

            $('.alertArea').append(alertErrorContent);
            $('.loader').css('display', 'none');
        }
    });
}

function clearTable(table) {
    table.find('tbody tr').each(function () {
        $(this).remove();
    });
}
function removeAlertLabel(classId) {
    $(classId).remove();
}