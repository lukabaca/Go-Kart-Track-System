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
            { "data": "start_date", "name": "start_date",   "targets": 0, "defaultContent": "-", "className":"start-date"},
            { "data": "end_date", "name": "end_date",   "targets": 1, "defaultContent": "-", "className":"end-date"},
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/laps/userSessionLaps/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('lapSession-row').attr('lapSession-id', data.id);
        },
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[0, 'asc'], [1, 'asc']],
    });
    $('.table tbody').on('click', '.lapSession-row', function (e) {
        e.preventDefault();
        let sessionId = $(this).closest('tr').attr('lapSession-id');
        window.location.href = '/laps/lapsForSession/' + sessionId;
    });
});