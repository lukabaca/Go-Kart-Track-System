$(document).ready(function () {
    let lapSessionTable = $('.lapSessionTable').DataTable({
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
        ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/laps/sessionLaps/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('lapSession-row').attr('lapSession-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[0, 'asc'], [1, 'asc']],
    });
});