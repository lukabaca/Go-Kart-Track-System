let calendarViewType = {'day': 1,'week': 2,'month': 3};
let calendarDefaultView = {1 : 'agendaDay', 2 : 'agendaWeek', 3 : 'month'};
$(document).ready(function () {
    let actualDate = new Date();
    defaultView = 'agendaWeek';
    getReservations(getProperDateFormat(actualDate), calendarViewType['week'], actualDate);
});
function initCalendar(eventArray, defaultView) {
    $('#calendar').fullCalendar({
        customButtons: {
            chooseDay: {
                text: 'Wybierz dzień',
                click: function () {
                    $('.datepicker').datepicker('show');
                    console.log('wybierz dzień');
                }
            },
            myDay: {
                text: 'Dzień',
                click: function () {
                    loadReservationsForCertainView('day');
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                    console.log('dzień');
                }
            },
            myWeek: {
                text: 'Tydzień',
                click: function () {
                    loadReservationsForCertainView('week');
                    $('#calendar').fullCalendar('changeView', 'agendaWeek');
                    console.log('tydzień');
                }
            },
            myMonth: {
                text: 'Miesiąc',
                click: function () {
                    loadReservationsForCertainView('month');
                    $('#calendar').fullCalendar('changeView', 'month');
                    console.log('miesiąc');
                }
            },
            myToday: {
                text: 'Dzisiaj',
                click: function () {
                    $('#calendar').fullCalendar('today');
                    loadReservationsForCertainView('day');
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                    console.log('dzisiaj');
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
                    console.log('wstecz');
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
                    console.log('do przodu');
                }
            },
        },
        firstDay: 1,
        allDayText: 'Godziny',
        allDayDefault: false,
        minTime: '11:30',
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
        //slotLabelInterval pomysl czy ma zostac czy nie
        // slotLabelInterval: 10,
        // //might be used in future
        // // slotDuration: '00:15:00',
        //
        // eventClick: function(calEvent, jsEvent, view) {
        //     window.location.href = "meeting/detailsReservation/" + calEvent.id;
        // },
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
        let colorPrivate = '##b3e5fc';
        let colorTeam = '#f56954 ';
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
                    $('.errorReservationsForHall').text('');
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
                        (reservations[i].private) ? (backgroundColor = colorPrivate) : (backgroundColor = colorTeam);
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
                } else {
                    stopLoadingProgress();
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                // window.location.href = '/404';
                console.log(statusCode);
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
