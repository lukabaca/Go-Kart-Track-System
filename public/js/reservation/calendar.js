let calendarViewType = {'day': 1,'week': 2,'month': 3};
let calendarDefaultView = {1 : 'agendaDay', 2 : 'agendaWeek', 3 : 'month'};
$(document).ready(function () {
    let actualDate = new Date();
    defaultView = 'agendaWeek';
    getReservations(getProperDateFormat(actualDate), calendarViewType['week'], actualDate);
    $.fn.datepicker.dates['pl'] = {
        days: ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"],
        daysShort: ["niedz", "pon", "wt", "śr", "czw", "pt", "sob"],
        daysMin: ["niedz", "pon", "wt", "śr", "czw", "pt", "sob"],
        months: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        monthsShort: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    };
    $('.datepicker').datepicker({
        weekStart: 1,
        // format: 'dd-mm-yyyy',
        language: 'pl',
        onSelect: function() {
        }
    }).on("change", function() {
        $('#calendar').fullCalendar( 'gotoDate', this.value );
        let moment = $('#calendar').fullCalendar('getDate');
        let date = moment.format('YYYY-MM-DD');
        getReservations(date, calendarViewType['day'], moment);
        $('#calendar').fullCalendar('changeView', 'agendaDay');
        $(this).datepicker('setDate', null);
    });
});

function initCalendar(eventArray, defaultView) {
    $('#calendar').fullCalendar({
        customButtons: {
            chooseDay: {
                text: 'Wybierz dzień',
                click: function () {
                    $('.datepicker').datepicker('show');
                }
            },
            myDay: {
                text: 'Dzień',
                click: function () {
                    loadReservationsForCertainView('day');
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                }
            },
            myWeek: {
                text: 'Tydzień',
                click: function () {
                    loadReservationsForCertainView('week');
                    $('#calendar').fullCalendar('changeView', 'agendaWeek');
                }
            },
            myMonth: {
                text: 'Miesiąc',
                click: function () {
                    loadReservationsForCertainView('month');
                    $('#calendar').fullCalendar('changeView', 'month');
                }
            },
            myToday: {
                text: 'Dzisiaj',
                click: function () {
                    $('#calendar').fullCalendar('today');
                    loadReservationsForCertainView('day');
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                }
            },
            myLeftArrow: {
                text: '<',
                click: function () {
                    $('#calendar').fullCalendar('prev');
                    let calendarViewType = getCalendarViewType();
                    loadReservationsForCertainView(calendarViewType);
                    let actualView = $('#calendar').fullCalendar('getView');
                    $('#calendar').fullCalendar('changeView', actualView.name);
                }
            },
            myRightArrow: {
                text: '>',
                click: function () {
                    $('#calendar').fullCalendar('next');
                    let calendarViewType = getCalendarViewType();
                    loadReservationsForCertainView(calendarViewType);
                    let actualView = $('#calendar').fullCalendar('getView');
                    $('#calendar').fullCalendar('changeView', actualView.name);
                }
            },
        },
        firstDay: 1,
        allDayText: 'Godziny',
        allDayDefault: false,
        minTime: '9:00',
        axisFormat: 'H:mm',
        timeFormat: 'H:mm',
        defaultView: defaultView,
        monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        monthNamesShort: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
        dayNames: ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa',
            'Czwartek', 'Piątek', 'Sobota'],
        dayNamesShort: ['niedz.', 'pon.', 'wt.', 'śr.', 'czw.', 'pt.', 'sob.'],
        header: {
            left: 'myLeftArrow, myRightArrow, myToday, chooseDay',
            center: 'title',
            right: 'myDay, myWeek, myMonth'
        },
        buttonText: {
            today: 'dzisiaj',
            month: 'miesiąc',
            week: 'tydzień',
            day: 'dzień'
        },
        height: 600,
        contentHeight: 600,
        aspectRatio: 2,
        events: eventArray,
        slotLabelFormat: 'H:mm',
        editable: false,
        slotDuration: '00:10:00',
        eventRender: function(eventObj, element) {
            let startDate = eventObj.start.toDate();
            let endDate = eventObj.end.toDate();
            let day = startDate.getDate() + '-' + startDate.getMonth() + '-' +  startDate.getFullYear();
            let hourAndMinuteStart = createProperHourMinuteFormat(startDate.getHours()) + ':' + createProperHourMinuteFormat(startDate.getMinutes());
            let hourAndMinuteEnd = createProperHourMinuteFormat(endDate.getHours()) + ':' + createProperHourMinuteFormat(endDate.getMinutes());
            let content =
                '<p>'+day+'</p' +
                '<span>'+hourAndMinuteStart + '-' + hourAndMinuteEnd+'</span>';
            element.popover({
                html: true,
                trigger: 'hover',
                content: '<p>'+day+'</p>' +
                    '<span>'+'<strong>'+hourAndMinuteStart+'</strong>' + '-' + '<strong>'+hourAndMinuteEnd+'</strong>'+'</span>',
                placement: 'top',
                container: 'body',
                animation: true,
            });
        },
    });
}
    function destroyCalendar() {
        $('#calendar').fullCalendar('destroy');
    }
    function startLoadingProgress() {
        $('.loader').css('display', 'block');
    }
    function stopLoadingProgress() {
        $('.loader').css('display', 'none');
    }
    function loadReservationsForCertainView(viewName) {
        let moment = $('#calendar').fullCalendar('getDate');
        let date = moment.format('YYYY-MM-DD');
        getReservations(date, calendarViewType[viewName], moment);
    }
    function getCalendarViewType() {
        let actualView = $('#calendar').fullCalendar('getView');
        let calendarViewType = getProperFormatCalendarViewType(actualView.name);
        return calendarViewType;
    }
    function getProperFormatCalendarViewType(viewName) {
        let type;
        switch (viewName) {
            case 'agendaDay':
            {
                type = 'day';
                break;
            }
            case 'agendaWeek':
            {
                type = 'week';
                break;
            }
            case 'month':
            {
                type = 'month';
                break;
            }
        }
        return type;
    }
    function getReservations(date, viewType, calendarActualDate) {
        startLoadingProgress();
        let colorUserReservation = '##b3e5fc';
        let colorTimeReservation = '#f56954 ';
        let borderColor = '#424242 ';
        let backgroundColor;
        let className = 'eventCalendar';
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/reservation/getReservations/' + date + '/' + viewType,
            success: function (data) {
                let reservations = data.reservations;
                if(reservations !== null && reservations !== undefined) {
                    let reservationsEvents = [];
                    let isValid = true;
                    for (let i = 0; i < reservations.length; i++) {
                        let id = (reservations[i].id === null || reservations[i].id === undefined) ? (isValid = false) : reservations[i].id;
                        let start = (reservations[i].start === null || reservations[i].start === undefined) ? (isValid = false) : reservations[i].start;
                        let end = (reservations[i].end === null || reservations[i].end === undefined) ? (isValid = false) : reservations[i].end;

                        let yearStart = (start.year === null || start.year === undefined) ? (isValid = false) : start.year;
                        let monthStart = (start.month === null || start.month === undefined) ? (isValid = false) : start.month;
                        let dayStart = (start.day === null || start.day === undefined) ? (isValid = false) : start.day;
                        let hourStart = (start.hour === null || start.hour === undefined) ? (isValid = false) : start.hour;
                        let minuteStart = (start.minute === null || start.minute === undefined) ? (isValid = false) : start.minute;

                        let yearEnd = (end.year === null || end.year === undefined) ? (isValid = false) : end.year;
                        let monthEnd = (end.month === null || end.month === undefined) ? (isValid = false) : end.month;
                        let dayEnd = (end.day === null || end.day === undefined) ? (isValid = false) : end.day;
                        let hourEnd = (end.hour === null || end.hour === undefined) ? (isValid = false) : end.hour;
                        let minuteEnd = (end.minute === null || end.minute === undefined) ? (isValid = false) : end.minute;

                        //with month there is -1 because js calendar starts numbering months from 0
                        monthStart = monthStart - 1;
                        monthEnd = monthEnd - 1;
                        (reservations[i].timeReservationType == 1) ? (backgroundColor = colorUserReservation) : (backgroundColor = colorTimeReservation);
                        if(isValid) {
                            reservationsEvents.push({
                                id: id,
                                start: new Date(yearStart, monthStart, dayStart, hourStart, minuteStart),
                                end: new Date(yearEnd, monthEnd, dayEnd, hourEnd, minuteEnd),
                                backgroundColor: backgroundColor,
                                borderColor: borderColor,
                                className: className,
                            });
                        }
                        isValid = true;
                    }
                    destroyCalendar();
                    let calendarView = calendarDefaultView[viewType];
                    initCalendar(reservationsEvents, calendarView);
                    stopLoadingProgress();
                    $('#calendar').fullCalendar( 'gotoDate', calendarActualDate);
                    $('#emptyCalendarHeader').text('');
                } else {
                    stopLoadingProgress();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                stopLoadingProgress();
            }
        });
    }
function getProperDateFormat(date) {
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();
    if(day < 10) {
        day = '0' + day;
    }
    if(month < 10) {
        month = '0' + month;
    }
    let dateFormatted = year + '-' + month + '-' + day;
    return dateFormatted;
}

function createProperHourMinuteFormat(element) {
    if (element < 10) {
        element = '0' + element;
    }
    return element;
}