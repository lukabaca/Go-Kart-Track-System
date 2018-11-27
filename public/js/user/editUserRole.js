$(document).ready(function () {
    $('#confirmChangesBtn').on('click', function (e) {
       e.preventDefault();
        let userId = $('.user-info-list').attr('user-id');
        let roleId = $('#roleSelect').find(':selected').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/user/editUserRole/' + userId + '/' + roleId,
            success: function (data) {
                window.location.href = '/user/manageUsers';
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let statusCode = xhr.status;
                let messageResponse = '';
                switch (statusCode) {
                    default : {
                        window.location.href = '/status500';
                        break;
                    }
                }
            }
        });
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