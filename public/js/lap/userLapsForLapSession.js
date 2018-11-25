$(document).ready(function () {
    $('.table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {extend: 'pdf', className: 'btn btn-primary tableButton'},
            {extend: 'excel', className: 'btn btn-primary tableButton', charset: 'utf-8', fieldSeparator: ',',
                fieldBoundary: '',},
            {extend: 'csv', className: 'btn btn-primary tableButton', charset: 'utf-8', fieldSeparator: ',',
                fieldBoundary: '',},
        ],
        // 'csv', 'excel', 'pdf'
        "columns": [
            { "width": "25%" },
            { "width": "25%" },
            { "width": "25%" },
            { "width": "25%" },
        ],
        "language": {
            "lengthMenu": "Wybierz _MENU_ rekordów na strone",
            "zeroRecords": "Brak danych",
            "info": "Strona _PAGE_ z _PAGES_",
            "infoEmpty": "Brak danych",
            "infoFiltered": "(przefiltrowane z _MAX_ wszystkich rekordów)",
            "search": "Szukaj",
            "paginate": {
                "previous": "Poprzednia strona",
                "next": "Następna strona",
            },
            "processing": "Wczytywanie...",
        },
        "order": [[0, 'asc']],
    });
});