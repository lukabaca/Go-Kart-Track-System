$(document).ready(function () {
    $('.table').DataTable({
    });
    $('.deleteReservationBtn').on('click', function (e) {
       e.preventDefault();
       let reservationId = $(this).closest('tr').attr('reservation-id');
       console.log(reservationId);
    });
});