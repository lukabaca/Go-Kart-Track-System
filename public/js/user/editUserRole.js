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
                switch (statusCode) {
                    default : {
                        break;
                    }
                }
            }
        });
    });
});