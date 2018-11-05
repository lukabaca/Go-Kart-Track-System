$(document).ready(function () {
    $('.table').DataTable({
        "columnDefs": [
            { "data": "name", "name": "name",   "targets": 0, "defaultContent": "-", },
            { "data": "prize", "name": "prize",   "targets": 1, "defaultContent": "-", },
            { "data": "availability", "name": "availability",  "targets": 2, "defaultContent": "-", },
        ],
        // "columnDefs": [
        //     { "data": "name", "name": "name"},
        //     { "data": "prize", "name": "prize"},
        //     { "data": "availability", "name": "availability"},
        // ],
        // "columns": [
        //     { "data": "name" },
        //     { "data": "prize" },
        //     { "data": "availability" },
        // ],
        // Server-side parameters
        "processing": true,
        "serverSide": true,
        // Ajax call
        "ajax": {
            "url": '/vehicle/datatable',
            // "url": "{{ path('/vehicle/datatable') }}",
            "type": "POST",
            // success: function (data) {
            //
            // },
            // error: function (error) {
            //
            // }
        },
        // Classic DataTables parameters
        "paging" : true,
        "info" : true,
        "searching": true,
        "pageLength": 10,
        "order": [[2, 'asc']],
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