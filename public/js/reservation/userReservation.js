$(document).ready(function () {
    let reservation_id;
    $('.table').DataTable({
        "columns": [
            { "width": "25%" },
            { "width": "25%" },
            { "width": "25%" },
            { "width": "5%" }
        ]
    });
    $('.deleteReservationBtn').on('click', function (e) {
       e.preventDefault();
       reservation_id = $(this).closest('tr').attr('reservation-id');
       $('#reservationDeleteConfirmationModal').modal('show');
    });
    $('#confirmDeletetingReservationBtn').on('click', function (e) {
       e.preventDefault();
       console.log('usun rezerwacje');
       if(reservation_id !== null && reservation_id !== undefined) {
           $.ajax({
               type: 'POST',
               dataType: 'json',
               url: '/reservation/deleteReservation/' + reservation_id,
               success: function (data) {
                    console.log('usunieto rezerwacje');
                    let reservationTable = $('.reservationTable');
                    deleteReservationFromTable(reservationTable, reservation_id);
                    successAlert('Poprawnie anulowano rezerwacje');
               },
               error: function (xhr, ajaxOptions, thrownError) {
                   let statusCode = xhr.status;
                   let errorMessage;
                   console.log(statusCode);
                   switch (statusCode) {
                       case 403: {
                           errorMessage = 'Nie można usuwać czyichś rezerwacji';
                           break;
                       }
                       default : {
                           window.location.href = '/status500';
                           break;
                       }
                   }
                   errorAlert(errorMessage);
               }
           });
       }
    });
});

function deleteReservationFromTable(table, reservation_id) {
    table.find('tbody tr').each(function () {
        let actualReservationId = $(this).attr('reservation-id');
        if(actualReservationId == reservation_id) {
            $(this).remove();
        }
    });
}
function successAlert(message) {
    let alertSuccessContent =
        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">X</span>' +
        '</button>' +
        '<strong>'+message+'</strong>' +
        '</div>';
    $('.alertArea').append(alertSuccessContent);
}
function errorAlert(message) {
    let alertErrorContent =
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">X</span>' +
        '</button>' +
        '<strong>'+message+'</strong>' +
        '</div>';
    $('.alertArea').append(alertErrorContent);
}