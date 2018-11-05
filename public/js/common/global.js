$(document).ready(function () {
    $('.goBack').on('click', function (e) {
       e.preventDefault();
       window.history.back();
    });
});