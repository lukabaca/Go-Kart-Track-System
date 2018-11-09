$(document).ready(function () {
   $('.table').DataTable({
        "stripeClasses": [],
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
            { "data": "name", "name": "name",   "targets": 0, "defaultContent": "-", },
            { "data": "prize", "name": "prize",   "targets": 1, "defaultContent": "-", },
            { "data": "availability", "name": "availability",  "targets": 2, "defaultContent": "-", },
        ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/vehicle/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            // Set the data-status attribute, and add a class
            $(row).addClass('kart-row').attr('kart-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[1, 'asc']],
    });
    $('.table tbody').on( 'click', 'tr', function (e) {
        e.preventDefault();
        let kartId = $(this).attr('kart-id');
        console.log(kartId);
        window.location.href = '/vehicle/addKart/' + kartId;
    });
});