let isPageReady = [
    {
        'name': 'loadedTimePerRide',
        'status': false
    },
    {
        'name': 'loadedTrackWorkingHours',
        'status': false
    },
    {
        'name': 'loadedKarts',
        'status': false
    },
];
$(document).ready(function () {
    let maxNumberOfDays = 21;
    let minNumberOfRides = 1;
    let today = new Date();
    let maxEndDate = new Date();
    maxEndDate.setDate(maxEndDate.getDate() + maxNumberOfDays);

    let timePerOneRide = 0;
    let numberOfRides = 1;

    let reserveButton = $('#reserveBtn');
    reserveButton.attr("disabled", "disabled");

    let isValidHourStart = false;
    let isValidNumberOfRides = true;
    let isValidChosenKarts = false;
    let isValidDate = true;

    let karts;
    let totalPrize = 0;

    let trackHourStart = '12:00';
    let trackHourEnd = '22:00';

    let trackStartTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 12, 0);
    let trackEndTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 22, 0);

    $('#numberOfRidesInput').val(minNumberOfRides);
    startLoadingProgress();
    loadTimePerOneRide();
    loadTrackWorkingHours();
    loadKarts();
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
        autoclose: true,
    });
    $('.datePicker').datepicker('setDate', today);
    $('#numberOfRidesInput').on('change', function (e) {
        e.preventDefault();
        numberOfRides = $(this).val();
        console.log(numberOfRides);
        if($('#hourStartInput').val() !== '' && numberOfRides !== '' && numberOfRides > 0) {
            let startTime = $('#hourStartInput').val();
            let hourAndMinuteStartTime = startTime.split(':');
            let hourAndMinuteEndTime = getEndTime(startTime, numberOfRides, timePerOneRide);
            let arrayEnd = hourAndMinuteEndTime.split(':');
            let dateEnd = new Date(trackEndTime.getFullYear(), trackEndTime.getMonth(), trackEndTime.getDate(), arrayEnd[0], arrayEnd[1]);
            let dateStart = new Date(trackEndTime.getFullYear(), trackEndTime.getMonth(), trackEndTime.getDate(), hourAndMinuteStartTime[0], hourAndMinuteStartTime[1]);
            /*popraw porownywanie samych godzin i minut a nie dat calych, bo mzoe przejsc z inputa na nastepny dzien i wtedy sie wywali */
            if(dateEnd <= trackEndTime) {
                isValidNumberOfRides = true;
                $('#hourEndInput').val(hourAndMinuteEndTime);
            } else {
                let minutesBetweenDates = getMinutesBetweenDates(dateStart, trackEndTime);
                $(this).val(minutesBetweenDates / timePerOneRide);
            }
        } else {
            $('#hourEndInput').val('');
            isValidNumberOfRides = false;
        }
        if(numberOfRides !== '') {
            let kartTable = $('#kartTable');
            let kartIds = getKartIdsFromTable(kartTable);
            getTotalPrizeForKartsInReservation(kartIds, numberOfRides);
        }
        checkButtonStatus();
    });

    $('#dateInput').on('change', function (e) {
       e.preventDefault();
       let date = $(this).val();
       if(date !== '') {
           isValidDate = true;
       } else {
           isValidDate = false;
       }
        checkButtonStatus();
    });

    function loadKarts() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/reservation/getKarts',
            success: function (data) {
                if(data.length > 0) {
                    karts = data;
                } else {

                }
                setIsPageReadyStatus('loadedKarts', true);
                showReservationForm();
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
            }
        });
    }
    function checkButtonStatus() {
        if(isValidHourStart && isValidNumberOfRides && isValidChosenKarts && isValidDate) {
            reserveButton.removeAttr("disabled");
        } else {
            reserveButton.attr("disabled", "disabled");
        }
    }

    $('#kartBtn').on('click', function (e) {
        e.preventDefault();
        startLoadingProgress();
        let table = $('#kartTableModal');
        clearTable(table);
        let isValid = true;
        for(let i = 0; i < karts.length; i++) {
            let id = (karts[i].id === null || karts[i].id === undefined) ? isValid = false : karts[i].id;
            let name = (karts[i].name === null || karts[i].name === undefined) ? isValid = false : karts[i].name;
            let prize = (karts[i].prize === null || karts[i].prize === undefined) ? isValid = false : karts[i].prize;
            let availability = (karts[i].availability === null || karts[i].availability === undefined) ? isValid = false : karts[i].availability;
            if(isValid && availability == 1) {
                let recordContent =
                    '<tr class="record-row" kart-id=' + id + '>' +
                    '<td class="record-info-td">'+'<input type="checkbox" class="form-check-input" value="' + id + '">'+'</td>' +
                    '<td class="record-info-td">' + name + '</td>' +
                    '<td class="record-info-td">' + prize + 'zł</td>' +
                    '</tr>';
                table.find('tbody').append(recordContent);
                isValid = true;
            }
        }
        stopLoadingProgress();
    });
    $('body').on('click', '.deleteKartIcon', function (e) {
        e.stopPropagation();
        let table = $('#kartTable');
        let tr = $(this).closest('.record-row');
        let kartId = tr.attr('kart-id');
        let kart = getKartById(karts, kartId);
        let numberOfRides = $('#numberOfRidesInput').val();
        if(kart) {
            tr.remove();
            // totalPrize -= kart.prize;
            let kartTable = $('#kartTable');
            let kartIds = getKartIdsFromTable(kartTable);
            getTotalPrizeForKartsInReservation(kartIds, numberOfRides);
            let numberOfRowsInTable = table.find('tbody tr').length;
            if (numberOfRowsInTable < 1) {
                isValidChosenKarts = false;
                totalPrize = '';
            }
            setPrizeInfo($('#reservationPrize'), totalPrize);
        } else {
            //error bo nie znalazlem katalogu, ups cos poszlo nie tak?
        }
        checkButtonStatus();
    });

    $('#confirmKartsModalBtn').on('click', function (e) {
        e.preventDefault();
        let kartTableModal = $('#kartTableModal');
        let kartTable = $('#kartTable');
        let checkedIds = getCheckedElementsIdsFromTable(kartTableModal);
        let chosenKartIds = [];
        kartTable.find('tbody tr').each(function () {
            let kartId = $(this).attr('kart-id');
            chosenKartIds.push(kartId);
        });
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
        for(let i = 0; i < kartIdsToLoad.length; i++) {
            startLoadingProgress();
            for(let j = 0; j < karts.length; j++) {
                if(kartIdsToLoad[i] == karts[j].id) {
                    let isValid = true;
                    let id = karts[j].id;
                    let name = (karts[j].name === null || karts[j].name === undefined) ? isValid = false : karts[j].name;
                    let prize = (karts[j].prize === null || karts[j].prize === undefined) ? isValid = false : karts[j].prize;
                    let availability = (karts[j].availability === null || karts[j].availability === undefined) ? isValid = false : karts[j].availability;
                    if (isValid) {
                        let recordContent =
                            '<tr class="record-row" kart-id=' + id + '>' +
                            '<td class="record-info-td">' + name + '</td>' +
                            '<td class="record-info-td">' + prize + 'zł</td>' +
                            '<td class="record-info-td">' + '<i class="fa fa-trash deleteKartIcon float-right" aria-hidden="true">' + '</i>' + '</td>' +
                            '</tr>';
                        kartTable.find('tbody').append(recordContent);
                        isValid = true;
                        isValidChosenKarts = true;
                        // totalPrize += prize;
                        checkButtonStatus();
                    }
                }
                stopLoadingProgress();
            }
        }
        let numberOfRides = $('#numberOfRidesInput').val();
        let kartIds = getKartIdsFromTable(kartTable);
        getTotalPrizeForKartsInReservation(kartIds, numberOfRides);
        stopLoadingProgress();
    });

    $('#reservationForm').submit(function (event) {
        event.preventDefault();
        let date = $('#dateInput').val();
        let hourStart = $('#hourStartInput').val();
        let hourEnd = $('#hourEndInput').val();
        let kartTable = $('#kartTable');
        let kartIds = getKartIdsFromTable(kartTable);
        let prize = $('#reservationPrize').text();
        let startDate = date + ' ' + hourStart;
        let endDate = date + ' ' + hourEnd;
        makeReservation(startDate, endDate, prize, false, null, kartIds);
    });
    function loadTimePerOneRide() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/reservation/getTimePerOneRide',
            success: function (data) {
                timePerOneRide = data.timePerRide;
                setIsPageReadyStatus('loadedTimePerRide', true);
                showReservationForm();
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
            }
        });
    }
    function loadTrackWorkingHours() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/reservation/getTrackConfig',
            success: function (data) {
               if(data.hourStart && data.hourEnd) {
                   // trackHourStart = data.hourStart;
                   // trackHourEnd = data.hourEnd;
                   trackStartTime = new Date(data.hourStart.date);
                   trackEndTime = new Date(data.hourEnd.date);
               } else {
                   trackStartTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 12, 0);
                   trackEndTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 22, 0)
                   // trackHourStart = '12:00';
                   // trackHourEnd = '22:00';
               }
               let startTrackHourAndMinutes = convertHourAndMinuteToProperFormat(trackStartTime);
               let endTrackHourAndMinutes = convertHourAndMinuteToProperFormat(trackEndTime);
               let trackStart = startTrackHourAndMinutes[0] + ':' + startTrackHourAndMinutes[1];
               let trackEnd = endTrackHourAndMinutes[0] + ':' + endTrackHourAndMinutes[1];
               setIsPageReadyStatus('loadedTrackWorkingHours', true);
               $('#trackWorkingHoursInfo').text('Godziny pracy toru: ' + trackStart + '-' + trackEnd);
               showReservationForm();
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
            }
        });
    }
    function initTimePickerOptions() {
        $('.timePicker').timepicker({
            timeFormat: 'HH:mm',
            interval: timePerOneRide,
            scrollbar: true,
            minTime: trackStartTime,
            maxTime: trackEndTime,
            dynamic: true,
            dropdown: true,
            change: function () {
                let time = $(this).val();
                if ($('#numberOfRidesInput').val() !== '' && time !== '') {
                    numberOfRides = $('#numberOfRidesInput').val();
                    isValidHourStart = true;
                    let hourAndMinuteEndTime = getEndTime(time, numberOfRides, timePerOneRide);
                    $('#hourEndInput').val(hourAndMinuteEndTime);
                } else {
                    $('#hourEndInput').val('');
                    isValidHourStart = false;
                }
                checkButtonStatus();
            }
        });
    }
    function showReservationForm() {
        let isPageReadyFinally = false;
        for(let i = 0; i < isPageReady.length; i++) {
            if(!isPageReady[i].status) {
                isPageReadyFinally = false;
                break;
            } else {
                isPageReadyFinally = true;
            }
        }
        if(isPageReadyFinally) {
            stopLoadingProgress();
            initTimePickerOptions();
            $('.reservation-area').css('display', 'flex');
        }
    }
});

function setPrizeInfo(element, prize) {
    element.text(prize);
}
function getKartById(karts, kartId) {
    for(let i = 0; karts.length; i++) {
        if(kartId == karts[i].id) {
            return karts[i];
        }
    }
    return null;
}
function getKartIdsFromTable(table) {
    let kartIds = [];
    table.find('tbody tr').each(function () {
        let kartId = $(this).attr('kart-id');
        kartIds.push(kartId);
    });
    return kartIds;
}
function getTotalKartPrizeFromTable(table) {
    let totalKartPrize = 0;
    table.find('tbody tr td').each(function () {
        let kartPrize = $(this).attr('kart-prize');
        totalKartPrize = totalKartPrize + kartPrize;
    });
    return kartIds;
}
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

function clearTable(table) {
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
        }
    });
    return checkedIds;
}
function getTotalPrizeForKartsInReservation(kartIds, numberOfRides) {
    if(numberOfRides > 0) {
        let kartData = {
            "numberOfRides": numberOfRides,
            "karts": kartIds,
        };
        kartData = JSON.stringify(kartData);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/reservation/getTotalKartPrizeForReservation',
            data: {
                kartData: kartData
            },
            success: function (data) {
                let totalPrize = data;
                if (totalPrize > 0) {
                    setPrizeInfo($('#reservationPrize'), totalPrize);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                switch (statusCode) {
                    // case 400: {

                    //     responseElement.text('Nie można sparsować przesłanych dat');
                    //     break;
                    // }
                    // case 404: {
                    //     // responseElement.text('Podano złe parametry');
                    //     break;
                    // }
                    // case 409: {
                    //     responseElement.text('Ten termin jest już zajęty');
                    //     break;
                    // }
                    default : {
                        window.location.href = '/status500';
                        break;
                    }
                }
            }
        });
    }
}
function makeReservation(startDate, endDate, cost, byTimeReservationType, description, karts) {
    let reservationData = {
        "startDate": startDate,
        "endDate": endDate,
        "cost": cost,
        "byTimeReservationType": byTimeReservationType,
        "description": description,
        "karts": karts,
    };
    reservationData = JSON.stringify(reservationData);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/reservation/makeReservation',
        data: {
            reservationData: reservationData
        },
        success: function (data) {
            console.log(data);
            resetForm();
            window.location.href = '/reservation/reservationDetails/' + data.id;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            let statusCode = xhr.status;
            responseElement = $('.reservationResponseErrorMessage');
            switch (statusCode) {
                case 400: {
                    responseElement.text('Wprowadzona data jest niepoprawna');
                    break;
                }
                case 404: {
                    // responseElement.text('Podano złe parametry');
                    break;
                }
                case 409: {
                    responseElement.text('Ten termin jest już zajęty');
                    break;
                }
                default : {
                    window.location.href = '/status500';
                    break;
                }
            }
            $('#reservationErrorResponseModal').modal('show');
        }
    });
}
function resetForm() {
    let today = new Date();
    $('#reservationForm')[0].reset();
    let kartTable = $('#kartTable');
    clearTable(kartTable);
    $('#reservationPrize').text('');
    $('#reserveBtn').attr("disabled", "disabled");
    $('.datePicker').datepicker('setDate', today);
}

function setInitialFlags(isValidHourStart, isValidNumberOfRides, isValidChosenKarts, isValidDate) {
    isValidHourStart = false;
    isValidNumberOfRides = true;
    isValidChosenKarts = false;
    isValidDate = true;
}
function getHourAsNumberFromTime(time) {
    if(time) {
        arraySplit = time.split(':');
        return arraySplit[0];
    }
}
function startLoadingProgress() {
    $('.loader').css('display', 'block');
}
function stopLoadingProgress() {
    $('.loader').css('display', 'none');
}
function setIsPageReadyStatus(name, status) {
    for(let i = 0; i < isPageReady.length; i++) {
        if(isPageReady[i].name === name) {
            isPageReady[i].status = status;
        }
    }
}
function getEndTime(startTime, numberOfRides, timePerOneRide) {
    let res = getHourAndMinutesFromTimePicker(startTime);
    let hour = res[0];
    let minute = res[1];
    let startDateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), hour, minute);
    let endDateTemp = startDateTemp.getTime() + getMilisecondsFromMinutes(numberOfRides * timePerOneRide);
    let endDate = new Date(endDateTemp);
    let finalTime = convertHourAndMinuteToProperFormat(endDate);
    let hourAndMinuteEndTime = finalTime[0] + ':' + finalTime[1];
    return hourAndMinuteEndTime;
}
function getMinutesBetweenDates(date1, date2) {
    let milisecondsBetweenDates = Math.abs(date1.getTime() - date2.getTime());
    let minutesBetweenDates = milisecondsBetweenDates / (1000 * 60);
    return minutesBetweenDates;
}