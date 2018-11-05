$(document).ready(function () {
    $('.table').DataTable({
    });
    $('.kart-row').on('click', function (e) {
       e.preventDefault();
       let kartId = $(this).attr('kart-id');
       window.location.href = '/vehicle/addKart/' + kartId;
    });
});