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

    initTimePickerOptions(hourStartInput, today, today, hourEndInput);
    initTimePickerOptions(hourEndInput, today, today, hourStartInput);

    // hourStartInput.on('change', function (e) {
    //     e.preventDefault();
    //     let time = $(this).val();
    //     console.log('start ', time);
    //     setMinTime(hourEndInput, time);
    // });
    // hourEndInput.on('change', function (e) {
    //     e.preventDefault();
    // });

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
    function initTimePickerOptions(element, minTime, maxTime, secondElement) {
        $(element).timepicker({
            timeFormat: 'HH:mm',
            interval: 10,
            scrollbar: true,
            dynamic: true,
            dropdown: true,
            change: function () {
                let time = $(this).val();
                // console.log(time);
                array = getHourAndMinutesFromTimePicker(time);
                console.log(array);
                time = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate(), array[0], array[1]);
                console.log(time);
                setMinTime(secondElement, time);
                checkButtonStatus();
            }
        });
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
