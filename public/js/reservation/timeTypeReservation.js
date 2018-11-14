$(document).ready(function () {
    let today = new Date();
    let reserveButton = $('#reserveBtn');
    let hourStartInput = $('#startHourInput');
    let hourEndInput = $('#endHourInput');
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
            array = getHourAndMinutesFromTimePicker(time);
            time = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), array[0], array[1]);
            setMinTime(hourEndInput, time);
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
            array = getHourAndMinutesFromTimePicker(time);
            time = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), array[0], array[1]);
            setMaxTime(hourStartInput, time);
            checkButtonStatus();
        }
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
        $(element).timepicker('option', 'minTime', minTime);
    }
    function setMaxTime(element, maxTime) {
        $(element).timepicker('option', 'maxTime', maxTime);
    }
});
function getHourAndMinutesFromTimePicker(time) {
    $res = time.split(':');
    return $res;
}
