$( document ).ready(function() {
    $('.arrowBack').on('click', function (e) {
       e.preventDefault();
       window.location.href = '/login';
    });
});