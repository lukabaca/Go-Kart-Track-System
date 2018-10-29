$(document).ready(function () {

    events = [
        {
            id: 1,
            title: 'a',
            start: new Date(2018, 9, 29, 10, 10),
            end: new Date(2018, 9, 29, 10, 45),
            backgroundColor: 'grey',
            borderColor: 'red',
        },
        // {
        //     id: 2,
        //     title: 'b',
        //     start: new Date(yearStart, monthStart, dayStart, hourStart, minuteStart),
        //     end: new Date(yearEnd, monthEnd, dayEnd, hourEnd, minuteEnd),
        //     backgroundColor: 'blue',
        //     borderColor: 'red',
        // },
        // {
        //     id: 3,
        //     title: 'c',
        //     start: new Date(yearStart, monthStart, dayStart, hourStart, minuteStart),
        //     end: new Date(yearEnd, monthEnd, dayEnd, hourEnd, minuteEnd),
        //     backgroundColor: 'blue',
        //     borderColor: 'red',
        // }
    ];
$('#calendar').fullCalendar({

    customButtons: {

        chooseDay: {
            text: 'Wybierz dzień',
            click: function() {
                $('.datepicker').datepicker('show');
                console.log('wybierz dzień');
            }
        },
        myDay: {
            text: 'Dzień',
            click: function() {
                // loadReservationsForCertainView('day');
                $('#calendar').fullCalendar('changeView', 'agendaDay');
                console.log('dzień');
            }

        },
        myWeek: {
            text: 'Tydzień',
            click: function() {
                // loadReservationsForCertainView('week');
                $('#calendar').fullCalendar('changeView', 'agendaWeek');
                console.log('tydzień');
            }
        },
        myMonth: {
            text: 'Miesiąc',
            click: function() {
                // loadReservationsForCertainView('month');
                $('#calendar').fullCalendar('changeView', 'month');
                console.log('miesiąc');
            }
        },
        myToday: {
            text: 'Dzisiaj',
            click: function() {
                $('#calendar').fullCalendar('today');
                // loadReservationsForCertainView('day');
                $('#calendar').fullCalendar('changeView', 'agendaDay');
                console.log('dzisiaj');
            }
        },

        myLeftArrow: {
            text: '<',
            click: function() {
                $('#calendar').fullCalendar('prev');
                //     let calendarViewType = getCalendarViewType();
                //     // loadReservationsForCertainView(calendarViewType);
                //
                //     let actualView = $('#calendar').fullCalendar('getView');
                //     $('#calendar').fullCalendar('changeView', actualView.name);
                //
                // }
                console.log('wstecz');
            }
        },

        myRightArrow: {
            text: '>',
            click: function() {
                $('#calendar').fullCalendar('next');
                // let calendarViewType = getCalendarViewType();
                // // loadReservationsForCertainView(calendarViewType);
                //
                // let actualView = $('#calendar').fullCalendar('getView');
                // $('#calendar').fullCalendar('changeView', actualView.name);
                console.log('do przodu');
            }

        },

    },

    firstDay: 1,

    allDayText: 'Godziny',

    allDayDefault: false,

    minTime: '7:00',

    axisFormat: 'H:mm',

    timeFormat: 'H:mm',

    aspectRatio: 2,

    defaultView: 'agendaWeek',

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

    height: 'parent',

    contentHeight: 600,

    events : events,

    slotLabelFormat: 'H:mm',

    editable  : false,

    // //might be used in future
    // // slotDuration: '00:15:00',
    //
    // eventClick: function(calEvent, jsEvent, view) {
    //     window.location.href = "meeting/detailsReservation/" + calEvent.id;
    // },
    });

});