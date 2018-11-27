$(document).ready(function () {
    let reservation_id;
    $('.deleteReservationBtn').on('click', function (e) {
        e.preventDefault();
        reservation_id = $('.reservationDetailsTable').attr('reservation-id');
        $('#reservationDeleteConfirmationModal').modal('show');
    });
    $('#confirmDeletetingReservationBtn').on('click', function (e) {
        e.preventDefault();
        if(reservation_id !== null && reservation_id !== undefined) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/reservation/deleteReservation/' + reservation_id,
                success: function (data) {
                    successAlert('Poprawnie anulowano rezerwacje');
                    $('.deleteReservationBtn').prop('disabled', true);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    let statusCode = xhr.status;
                    let errorMessage;
                    switch (statusCode) {
                        case 403: {
                            errorMessage = 'Nie można usuwać czyichś rezerwacji';
                            errorAlert(errorMessage);
                            break;
                        }
                        case 404: {
                            window.location.href = '/status404';
                            break;
                        }
                        default : {
                            window.location.href = '/status500';
                            break;
                        }
                    }
                }
            });
        }
    });
    $('.goBack').on('click', function (e) {
        e.preventDefault();
        window.history.back();
        window.location = document.referrer;
    });
});
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