$(document).ready(function () {
    let userTable = $('.userTable').DataTable({
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
            { "data": "name", "name": "name",   "targets": 0, "defaultContent": "-", },
            { "data": "surname", "name": "surname",   "targets": 1, "defaultContent": "-", },
            { "data": "birth_date", "name": "birth_date",  "targets": 2, "defaultContent": "-", },
            { "data": "pesel", "name": "pesel",  "targets": 3, "defaultContent": "-", },

            { "data": "document_id", "name": "document_id",  "targets": 4, "defaultContent": "-", },
            { "data": "email", "name": "email",  "targets": 5, "defaultContent": "-", },
            { "data": "telephone_number", "name": "telephone_number",  "targets": 6, "defaultContent": "-", },
            { "data": "role_name", "name": "role_name",  "targets": 7, "defaultContent": "-", },
        ],
        // "columns": [
        //     { "width": "25%" },
        //     { "width": "25%" },
        //     { "width": "10%" },
        //     { "width": "10%" },
        // ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/user/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('user-row').attr('user-id', data.id);
            // $(row).attr('user-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[1, 'asc']],
    });
});