$(document).ready(function () {
    let maxNumberOfDays = 21;
    let today = new Date();
    let maxEndDate = new Date();
    maxEndDate.setDate(maxEndDate.getDate() + maxNumberOfDays);

    let timePerOneRide;

    let chosenGokartsNumber = 0;
    let numberOfRides = 0;

    let reserveButton = $('#reserveBtn');
    let isDisabledBtn = true;

    reserveButton.attr("disabled", "disabled");

    let isValidHourStart = false;
    let isValidNumberOfRides = false;

    $.fn.datepicker.dates['pl'] = {
        days: ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"],
        daysShort: ["niedz", "pon", "wt", "śr", "czw", "pt", "sob"],
        daysMin: ["niedz", "pon", "wt", "śr", "czw", "pt", "sob"],
        months: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        monthsShort: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    };
    $('.datePicker').datepicker({
        todayHighlight: true,
        defaultTime: today,
        startDate: today,
        endDate: maxEndDate,
        weekStart: 1,
        format: 'dd-mm-yyyy',
        language: 'pl',
    });
    $('.datePicker').datepicker('setDate', today);

    $('.timePicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 30,
        scrollbar: true,
        minHour: 12,
        maxHour: 21,
        dynamic: true,
        dropdown: true,
        change: function () {
            let time = $(this).val();
            if(time !== '') {
                isValidHourStart = true;
                let res = getHourAndMinutesFromTimePicker(time);
                let hour = res[0];
                let minute = res[1];
                let startDateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), hour, minute);
                let testTime = startDateTemp.getTime() + getMilisecondsFromMinutes(numberOfRides * timePerOneRide);
                let test = new Date(testTime);


                if ($('#numberOfRidesInput').val() !== '' && time !== '') {
                    let finalTime = convertHourAndMinuteToProperFormat(test);

                    let hourAndMinuteEndTime = finalTime[0] + ':' + finalTime[1];
                    $('#hourEndInput').val(hourAndMinuteEndTime);
                } else {
                    $('#hourEndInput').val('');
                }
            } else {
                isValidHourStart = false;
            }
            checkButtonStatus();
        }
    });

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/reservation/getTimePerOneRide',
        success: function (data) {
            // console.log(data);
            timePerOneRide = data.timePerRide;
            // $('.loader').css('display', 'none');
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
                        '<strong>Wystąpił błąd podczas pobierania danych</strong>' +
                        '</div>';

                    $('.alertArea').append(alertErrorContent);
                    break;
                }
            }
            // $('.loader').css('display', 'none');
        }
    });

    $('#chosenGokartsNumber').text(chosenGokartsNumber + '/');


    $('#numberOfRidesInput').on('change', function (e) {
        e.preventDefault();
        numberOfRides = $(this).val();
        if($('#hourStartInput').val() !== '' && numberOfRides !== '') {
            isValidNumberOfRides = true;
            let time = $('#hourStartInput').val();
            let res = getHourAndMinutesFromTimePicker(time);
            let hour = res[0];
            let minute = res[1];
            let startDateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), hour, minute);
            let testTime = startDateTemp.getTime() + getMilisecondsFromMinutes(numberOfRides * timePerOneRide);
            let test = new Date(testTime);

            let finalTime = convertHourAndMinuteToProperFormat(test);

            let hourAndMinuteEndTime = finalTime[0] + ':' + finalTime[1];
            $('#hourEndInput').val(hourAndMinuteEndTime);
        } else {
            $('#hourEndInput').val('');
            isValidNumberOfRides = false;
        }

        checkButtonStatus();
    });

    function checkButtonStatus() {
        if(isValidHourStart && isValidNumberOfRides) {
            reserveButton.removeAttr("disabled");
        } else {
            reserveButton.attr("disabled", "disabled");
        }
    }

    $('#kartBtn').on('click', function (e) {
        $('.loader').css('display', 'block');
        let table = $('#kartTableModal');
        clearTable(table);
       e.preventDefault();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/reservation/getKarts',
            success: function (data) {
                let isValid = true;
                for(let i = 0; i < data.length; i++) {
                    let id = (data[i].id === null || data[i].id === undefined) ? isValid = false : data[i].id;
                    let name = (data[i].name === null || data[i].name === undefined) ? isValid = false : data[i].name;

                    if(isValid) {
                        let recordContent =
                            '<tr class="record-row" record-id=' + id + '>' +
                                '<td class="record-info-td">'+'<input type="checkbox" class="form-check-input" value="' + id + '">'+'</td>' +
                                '<td class="record-info-td">' + id + '</td>' +
                                '<td class="record-info-td">' + name + '</td>' +
                            '</tr>';

                        table.find('tbody').append(recordContent);
                        isValid = true;
                    }
                }
                // '<input type="checkbox" class="form-check-input" value="' + id + '">'
                $('.loader').css('display', 'none');
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
                            '<strong>Wystąpił błąd podczas pobierania danych</strong>' +
                            '</div>';

                        $('.alertArea').append(alertErrorContent);
                        break;
                    }
                }
                $('.loader').css('display', 'none');
            }
        });
    });
    $('body').on('click', '.deleteKartIcon', function (e) {
        e.stopPropagation();
        let tr = $(this).closest('.record-row');
        tr.remove();
    });


    $('#confirmKartsModalBtn').on('click', function (e) {

        let table = $('#kartTableModal');
        let table2 = $('#kartTable');
        e.preventDefault();
        let checkedIds = getCheckedElementsIdsFromTable(table);
        console.log(checkedIds);
        let chosenKartIds = [];
        table2.find('tbody tr').each(function () {
            let kartId = $(this).attr('record-id');
            chosenKartIds.push(kartId);
        });
        console.log(chosenKartIds);

        //delete ids that already exist in table
        let kartIdsToLoad = [];
        let flag = false;
        for (let i = 0; i < checkedIds.length; i++) {
            for (let j = 0; j < chosenKartIds.length; j++) {
                if(checkedIds[i] === chosenKartIds[j]) {
                    flag = true;
                }
            }
            if(!flag) {
                kartIdsToLoad.push(checkedIds[i]);
            }
            flag = false;
        }
        console.log(kartIdsToLoad);
        for(let i = 0; i < kartIdsToLoad.length; i++) {
            $('.loader').css('display', 'block');
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/reservation/getKart/' + kartIdsToLoad[i],
                success: function (data) {
                    console.log(data);
                    let isValid = true;
                        let id = (data.id === null || data.id === undefined) ? isValid = false : data.id;
                        let name = (data.name === null || data.name === undefined) ? isValid = false : data.name;

                        if(isValid) {
                            let recordContent =
                                '<tr class="record-row" record-id=' + id + '>' +
                                    '<td class="record-info-td">' + id + '</td>' +
                                    '<td class="record-info-td">' + name + '</td>' +
                                    '<td class="record-info-td">' + '<i class="fa fa-trash deleteKartIcon float-right" aria-hidden="true">' + '</i>' + '</td>' +
                                '</tr>';

                            table2.find('tbody').append(recordContent);
                            isValid = true;
                        }

                    // '<input type="checkbox" class="form-check-input" value="' + id + '">'
                    $('.loader').css('display', 'none');
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
                                '<strong>Wystąpił błąd podczas pobierania danych</strong>' +
                                '</div>';

                            $('.alertArea').append(alertErrorContent);
                            break;
                        }
                    }
                    // $('.loader').css('display', 'none');
                }
            });
        }
        $('.loader').css('display', 'none');
    });
});

function getHourAndMinutesFromTimePicker(time) {
    $res = time.split(':');
    return $res;
}

function getMilisecondsFromMinutes(minutes) {
    return minutes * 60 * 1000;
}

function convertHourAndMinuteToProperFormat(date) {
    let hour = date.getHours();
    let minute = date.getMinutes();

    if(hour < 10) {
        hour = '0' + hour;
    }
    if(minute < 10) {
        minute = '0' + minute;
    }
    res = [hour, minute];
    return res;
}

function clearTable(tableID) {
    let table = $(tableID);

    table.find('tbody tr').each(function () {
        $(this).remove();
    });
}

function getCheckedElementsIdsFromTable(tableID) {
    let table = $(tableID);
    let table2 = $('#kartTable');
    let checkedIds = [];
    table.find('tbody tr').each(function () {
        let trTemp = $(this);
        let kartIdChecked = trTemp.find('td input:checked').val();
        if(kartIdChecked !== '' && kartIdChecked !== undefined) {
            checkedIds.push(kartIdChecked);
            // let row = $(this);
            // row.find('td input:checked').remove();
            // let td = '<td class="record-info-td">' + '<i class="fa fa-trash deleteRecordingIcon float-right" aria-hidden="true">' + '</i>' + '</td>';
            // row.append(td);
            // table2.find('tbody').append(row);
        }

    });
    return checkedIds;
}