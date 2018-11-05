$(document).ready(function () {
    $('.table').DataTable({
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
        },
        "columnDefs": [
            { "data": "name", "name": "name",   "targets": 0, "defaultContent": "-", },
            { "data": "prize", "name": "prize",   "targets": 1, "defaultContent": "-", },
            { "data": "availability", "name": "availability",  "targets": 2, "defaultContent": "-", },
        ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        // Ajax call
        "ajax": {
            "url": '/vehicle/datatable',
            // "url": "{{ path('/vehicle/datatable') }}",
            "type": "POST",
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[1, 'asc']],
    });
    // let pos = 1;
    // table.columns().every( function () {
    //     var that = this;
    //
    //     $("#input"+pos).on( 'keyup change', function () {
    //         if ( that.search() !== this.value )
    //         {
    //             that
    //                 .search( this.value )
    //                 .draw();
    //         }
    //     });
    //     pos++;
    // });
    $('.kart-row').on('click', function (e) {
       e.preventDefault();
       let kartId = $(this).attr('kart-id');
       window.location.href = '/vehicle/addKart/' + kartId;
    });
});