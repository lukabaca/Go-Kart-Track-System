let flag = false;
$(document).ready(function () {
    let today = new Date();
    let defaultMinTime = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 0, 0);
    let defaultMaxTime = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), 23, 59);
    let reserveButton = $('#reserveBtn');
    let hourStartInput = $('#startHourInput');
    let hourEndInput = $('#endHourInput');
    let prizeInput = $('#reservationPrize');
    let dateInput = $('#dateInput');
    reserveButton.attr("disabled", "disabled");

    let isValidHourStart = false;
    let isValidHourEnd = true;
    let isValidPrice = false;
    let isValidDate = true;


    $(hourStartInput).timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        scrollbar: true,
        dynamic: true,
        dropdown: true,
        change: function () {
            let time = $(this).val();
            if(time !== '') {
                array = getHourAndMinutesFromTimePicker(time);
                time = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), array[0], array[1]);
                setMinTime(hourEndInput, time);
                isValidHourStart = true;
            } else {
                setMaxTime(hourEndInput, defaultMaxTime);
                setMinTime(hourEndInput, defaultMinTime);
                isValidHourStart = false;
            }
            checkButtonStatus();
        }
    });
    $(hourEndInput).timepicker({
        timeFormat: 'HH:mm',
        interval: 10,
        scrollbar: true,
        dynamic: true,
        dropdown: true,
        change: function () {
            let time = $(this).val();
            if(time !== '') {
                isValidHourEnd = true;
                array = getHourAndMinutesFromTimePicker(time);
                time = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), array[0], array[1]);
                setMaxTime(hourStartInput, time);
            } else {
                setMaxTime(hourStartInput, defaultMaxTime);
                setMinTime(hourStartInput, defaultMinTime);
                isValidHourEnd = false;
            }
            checkButtonStatus();
        }
    });
    $(dateInput).on('change', function (e) {
        e.preventDefault();
        let date = $(this).val();
        if(date !== '') {
            isValidDate = true;
        } else {
            isValidDate = false;
        }
        checkButtonStatus();
    });
    $(prizeInput).on('change', function (e) {
       e.preventDefault();
       let prize = $(this).val();
       if(prize !== '') {
           isValidPrice = true;
       } else {
           isValidPrice = false;
       }
        checkButtonStatus();
    });
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
        weekStart: 1,
        format: 'dd-mm-yyyy',
        language: 'pl',
    });
    $('.datePicker').datepicker('setDate', today);

    function checkButtonStatus() {
        if(isValidHourStart && isValidHourEnd && isValidPrice && isValidDate) {
            reserveButton.removeAttr("disabled");
        } else {
            reserveButton.attr("disabled", "disabled");
        }
    }
    function setMinTime(element, minTime) {
        if (flag === true) {
            return;
        }
        else {
            flag = true;
        }
        $(element).timepicker('option', 'minTime', minTime);
        flag = false;
    }
    function setMaxTime(element, maxTime) {
        if (flag === true) {
            return;
        }
        else {
            flag = true;
        }
        $(element).timepicker('option', 'maxTime', maxTime);
        flag = false;
    }
    $('#reservationForm').submit(function (event) {
        event.preventDefault();
        console.log('submit forma');
        let date = $('#dateInput').val();
        let hourStart = $('#startHourInput').val();
        let hourEnd = $('#endHourInput').val();
        let cost = $('#reservationPrize').val();
        let description = $('#reservationDescription').val();
        let startDate = date + ' ' + hourStart;
        let endDate = date + ' ' + hourEnd;
        if(date && hourStart && hourEnd && cost) {
            makeReservation(startDate, endDate, cost, true, description, null);
        }
    });
});
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
function getHourAndMinutesFromTimePicker(time) {
    $res = time.split(':');
    return $res;
}
function resetForm() {
    let today = new Date();
    $('#reservationForm')[0].reset();
}