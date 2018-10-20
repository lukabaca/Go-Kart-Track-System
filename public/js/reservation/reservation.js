$(document).ready(function () {
    $('#calendar').fullCalendar({


        // customButtons: {
        //
        //     chooseDay: {
        //         text: 'Wybierz dzień',
        //         click: function() {
        //             $('.datepicker').datepicker('show');
        //         }
        //     },
        //     myDay: {
        //         text: 'Dzień',
        //         click: function() {
        //             loadReservationsForCertainView('day');
        //             $('#calendar').fullCalendar('changeView', 'agendaDay');
        //         }
        //
        //     },
        //     myWeek: {
        //         text: 'Tydzień',
        //         click: function() {
        //             loadReservationsForCertainView('week');
        //             $('#calendar').fullCalendar('changeView', 'agendaWeek');
        //         }
        //     },
        //     myMonth: {
        //         text: 'Miesiąc',
        //         click: function() {
        //             loadReservationsForCertainView('month');
        //             $('#calendar').fullCalendar('changeView', 'month');
        //         }
        //     },
        //     myToday: {
        //         text: 'Dzisiaj',
        //         click: function() {
        //             $('#calendar').fullCalendar('today');
        //             loadReservationsForCertainView('day');
        //             $('#calendar').fullCalendar('changeView', 'agendaDay');
        //         }
        //     },
        //
        //     myLeftArrow: {
        //         text: '<',
        //         click: function() {
        //             $('#calendar').fullCalendar('prev');
        //             let calendarViewType = getCalendarViewType();
        //             loadReservationsForCertainView(calendarViewType);
        //
        //             let actualView = $('#calendar').fullCalendar('getView');
        //             $('#calendar').fullCalendar('changeView', actualView.name);
        //
        //         }
        //
        //     },
        //
        //     myRightArrow: {
        //         text: '>',
        //         click: function() {
        //             $('#calendar').fullCalendar('next');
        //             let calendarViewType = getCalendarViewType();
        //             loadReservationsForCertainView(calendarViewType);
        //
        //             let actualView = $('#calendar').fullCalendar('getView');
        //             $('#calendar').fullCalendar('changeView', actualView.name);
        //         }
        //
        //     },
        //
        // },

        firstDay: 1,

        allDayText: 'Godziny',

        allDayDefault: false,

        minTime: '7:00',

        axisFormat: 'H:mm',

        timeFormat: 'H:mm',

        aspectRatio: 2,

        // defaultView: defaultView,

        monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],

        monthNamesShort: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec',
            'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],

        dayNames: ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa',
            'Czwartek', 'Piątek', 'Sobota'],

        dayNamesShort: ['niedz.', 'pon.', 'wt.', 'śr.', 'czw.', 'pt.', 'sob.'],


        header    : {
            left  : 'myLeftArrow, myRightArrow, myToday, chooseDay',
            center: 'title',
            right : 'myDay, myWeek, myMonth'
        },

        buttonText: {
            today: 'dzisiaj',
            month: 'miesiąc',
            week : 'tydzień',
            day  : 'dzień'
        },

        height: 'auto',

        contentHeight: 'auto',

        // events : eventArray,

        slotLabelFormat: 'H:mm',

        editable  : false,

        // //might be used in future
        // // slotDuration: '00:15:00',
        //
        // eventClick: function(calEvent, jsEvent, view) {
        //     window.location.href = "meeting/detailsReservation/" + calEvent.id;
        // },

    })
});