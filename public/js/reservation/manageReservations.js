$(document).ready(function () {
    let kartTable = $('.reservationsTable').DataTable({
        "stripeClasses": [],
        "responsive": true,
        "language": {
            "lengthMenu": "Wybierz _MENU_ rekordów na strone",
            "zeroRecords": "Brak danych",
            "info": "Strona _PAGE_ z _PAGES_",
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
                "className":"reservation-typey",
                "render": function ( data, type, row, meta ) {
                    return (data == 1) ? 'Dostępny' : 'Niedostępny';
                },
            },
            // { "data": "user.name", "name": "user.name",   "targets": 1, "defaultContent": "-", },
        ],
        "columns": [
            { "width": "25%" },
            { "width": "25%" },
            { "width": "10%" },
            { "width": "10%" },
        ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/reservation/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('reservation-row').attr('reservation-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[1, 'asc']],
    });
});