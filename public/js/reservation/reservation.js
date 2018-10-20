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
    let isValidNumberOfPeople = false;

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
                    console.log(hourAndMinuteEndTime);
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

    $('#numberOfPeopleInput').on('change', function (e) {
        e.preventDefault();
        let numberOfPeople = $(this).val();
        if(numberOfPeople !== '') {
            isValidNumberOfPeople = true;
            $('#numberOfPeoplePerReservation').text(numberOfPeople);
        } else {
            isValidNumberOfPeople = false;
        }
        checkButtonStatus();
    });

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
            console.log(hourAndMinuteEndTime);
            $('#hourEndInput').val(hourAndMinuteEndTime);
        } else {
            $('#hourEndInput').val('');
            isValidNumberOfRides = false;
        }

        checkButtonStatus();
    });

    function checkButtonStatus() {
        if(isValidHourStart && isValidNumberOfPeople && isValidNumberOfRides) {
            console.log('weszlo');
            reserveButton.removeAttr("disabled");
        } else {
            console.log('nie weszlo');
            reserveButton.attr("disabled", "disabled");
        }
    }
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

