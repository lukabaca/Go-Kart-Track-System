$(document).ready(function () {
    let maxNumberOfDays = 21;
    let today = new Date();
    let maxEndDate = new Date();
    maxEndDate.setDate(maxEndDate.getDate() + maxNumberOfDays);

    let timePerOneRide;

    let chosenGokartsNumber = 0;

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
        default: 'now',
        startDate: today,
        endDate: maxEndDate,
        weekStart: 1,
        format: 'dd-mm-yyyy',
        language: 'pl',
    });

    $('.timePicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 30,
        scrollbar: true,
        minHour: 12,
        maxHour: 21,
        change: function () {
            let time = $(this).val();
            let res = getHourAndMinutesFromTimePicker(time);
            let hour = res[0];
            let minute = res[1];
            let startDateTemp = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), hour, minute);
            console.log(startDateTemp);
            let testTime = startDateTemp.getTime() + getMilisecondsFromMinutes(timePerOneRide);
            console.log(testTime);
            let test = new Date(testTime);
            console.log(test);

            console.log(getMilisecondsFromMinutes(timePerOneRide));
        }
    });

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/reservation/getTimePerOneRide',
        success: function (data) {
            // console.log(data);
            timePerOneRide = data.timePerRide;
            console.log(timePerOneRide);
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
        $('#numberOfPeoplePerReservation').text(numberOfPeople);
    });
});

function getHourAndMinutesFromTimePicker(time) {
    $res = time.split(':');
    return $res;
}

function getMilisecondsFromMinutes(minutes) {
    return minutes * 60 * 1000;
}
