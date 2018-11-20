$(document).ready(function () {
   $('.deleteNewsIcon').on('click', function (e) {
      e.preventDefault();
      console.log('usun');
       reservation_id = $(this).closest('tr').attr('reservation-id');
       $('#newsDeleteConfirmationModal').modal('show');
   });
    $('#confirmDeletetingNewsBtn').on('click', function (e) {
        e.preventDefault();
        // if(reservation_id !== null && reservation_id !== undefined) {
        //     $.ajax({
        //         type: 'POST',
        //         dataType: 'json',
        //         url: '/reservation/deleteReservation/' + reservation_id,
        //         success: function (data) {
        //             let reservationTable = $('.reservationTable');
        //             deleteReservationFromTable(reservationTable, reservation_id);
        //             successAlert('Poprawnie anulowano rezerwacje');
        //         },
        //         error: function (xhr, ajaxOptions, thrownError) {
        //             let statusCode = xhr.status;
        //             let errorMessage;
        //             switch (statusCode) {
        //                 case 403: {
        //                     errorMessage = 'Nie można usuwać czyichś rezerwacji';
        //                     errorAlert(errorMessage);
        //                     break;
        //                 }
        //                 case 404: {
        //                     window.location.href = '/status404';
        //                     break;
        //                 }
        //                 default : {
        //                     window.location.href = '/status500';
        //                     break;
        //                 }
        //             }
        //         }
        //     });
        // }
    });
});