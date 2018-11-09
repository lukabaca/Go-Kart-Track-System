$(document).ready(function () {
   let kartTable = $('.table').DataTable({
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
            { "data": "prize", "name": "prize",   "targets": 1, "defaultContent": "-", },
            { "data": "availability", "name": "availability",  "targets": 2, "defaultContent": "-", "className":"kart-availability"},
            { "data": "", "targets": 3, "orderable": false, "render":
                    function ( data, type, row ) {
                    return '<button class="btn btn-secondary editKartBtn"><i class="far fa-edit"></i></button>' +
                           '<button class="btn btn-secondary editAvailabilityBtn"><i class="fa fa-exchange-alt"></i></button>';
                    }
            },
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
            "url": '/vehicle/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('kart-row').attr('kart-id', data.id);
            $(row).attr('kart-id', data.id);
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[1, 'asc']],
    });
    $('.table tbody').on( 'click', '.editAvailabilityBtn', function (e) {
        e.preventDefault();
        let closestTr = $(this).closest('tr');
        let kartId = closestTr.attr('kart-id');
        let availability = closestTr.find('.kart-availability').text();
        availability = (availability == 1) ? '0' : '1';
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/vehicle/editKartAvailability/' + kartId + '/' + availability,
            success: function (data) {
                kartTable.ajax.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                switch (statusCode) {
                    default : {
                        break;
                    }
                }
            }
        });
    });
    $('.table tbody').on( 'click', '.editKartBtn', function (e) {
        e.preventDefault();
        let kartId = $(this).closest('tr').attr('kart-id');
        window.location.href = '/vehicle/addKart/' + kartId;
    });
});