$(document).ready(function () {
    $('#saveChangesBtn').on('click', function (e) {
       e.preventDefault();
       let userId = $('.user-info-list').attr('user-id');
       console.log(userId);
    });
});