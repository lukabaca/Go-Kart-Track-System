$(document).ready(function () {
    let kartTable = $('.reservationsTable').DataTable({
        "stripeClasses": [],
        "responsive": true,
        "language": {
            "lengthMenu": "Wybierz _MENU_ rekordów na strone",
            "zeroRecords": "Brak danych",
            "info": "_START_ do _END_ z _TOTAL_ wszystkich rekordów",
            "infoEmpty": "Brak danych",
            "infoFiltered": "(przefiltrowane z _MAX_ total records)",
            "search": "Szukaj",
            "paginate": {
                "previous": "Poprzednia strona",
                "next": "Następna strona",
            },
            "processing": "Wczytywanie...",
        },
        "columnDefs": [
            { "data": "start_date", "name": "start_date",   "targets": 0, "defaultContent": "-", },
            { "data": "end_date", "name": "end_date",   "targets": 1, "defaultContent": "-", },
            { "data": "cost", "name": "cost",   "targets": 2, "defaultContent": "-", },
            { "data": "by_time_reservation_type", "name": "by_time_reservation_type",  "targets": 3, "defaultContent": "-",
                // "className":"reservation-typey",
                "render": function ( data, type, row, meta ) {
                    return (data == 1) ? 'Czasowa' : 'Użytkownika';
                },
            },
            { "data": "name", "name": "name",   "targets": 4, "defaultContent": "-", },
            { "data": "surname", "name": "surname",   "targets": 5, "defaultContent": "-", },
        ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/reservation/datatable',
            "type": "POST",
        },
        "createdRow": function(row, data, dataIndex, cells) {
            if(data.by_time_reservation_type == 1) {
                $(row).addClass('timeTypeReservation');
            }
            $(row).addClass('reservation-row').attr('reservation-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[0, 'desc']],
    });
    $('.table tbody').on('click', '.reservation-row', function (e) {
        e.preventDefault();
        let reservationId = $(this).closest('tr').attr('reservation-id');
        window.location.href = '/reservation/reservationDetails/' + reservationId;
    });
});