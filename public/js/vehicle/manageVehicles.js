$(document).ready(function () {
   let kartTable = $('.table').DataTable({
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
            { "data": "name", "name": "name",   "targets": 0, "defaultContent": "-", },
            { "data": "prize", "name": "prize",   "targets": 1, "defaultContent": "-", },
            { "data": "availability", "name": "availability",  "targets": 2, "defaultContent": "-",
                "className":"kart-availability",
                "render": function ( data, type, row, meta ) {
                    return (data == 1) ? 'Dostępny' : 'Niedostępny';
                },
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('kart-availability', rowData.availability);
                }
            },
            { "data": "", "targets": 3, "orderable": false, "render":
                    function ( data, type, row ) {
                    return '<div class="optionsArea">' +
                        '<button class="btn btn-data-info editKartBtn"><i class="far fa-edit"></i></button>' +
                        '<button class="btn btn-secondary editAvailabilityBtn"><i class="fa fa-exchange-alt"></i></button>' +
                        '</div>';
                    }
            },
        ],
       "columns": [
           { "width": "25%" },
           { "width": "25%" },
           { "width": "10%" },
           { "width": "10%" },
       ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/vehicle/datatable',
            "type": "POST",
        },
        createdRow: function(row, data, dataIndex, cells) {
            $(row).addClass('kart-row').attr('kart-id', data.id);
        },
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[2, 'desc']],
    });
    $('.table tbody').on( 'click', '.editAvailabilityBtn', function (e) {
        e.preventDefault();
        let closestTr = $(this).closest('tr');
        let kartId = closestTr.attr('kart-id');
        let availability = closestTr.find('.kart-availability').attr('kart-availability');
        availability = (availability == 1) ? '0' : '1';
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/vehicle/editKartAvailability/' + kartId + '/' + availability,
            success: function (data) {
                let availability = (data.availability == 1) ? 'Dostępny' : 'Niedostępny';
                let td = closestTr.find('td.kart-availability');
                td.text(availability);
                $(td).attr('kart-availability', data.availability);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                switch (statusCode) {
                    default : {
                        window.location.href = '/status500';
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