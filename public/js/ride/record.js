$(document).ready(function (e) {
    $('#allTimeRecord').on('click', function (e) {
       e.preventDefault();
       console.log('rekord wszech czasow');
    });

    $('#monthRecord').on('click', function (e) {
        e.preventDefault();
        console.log('rekord miesiaca');
    });

    $('#weekRecord').on('click', function (e) {
        e.preventDefault();
        console.log('rekord tygodnia');
    });

});