$(document).ready(function () {
    let newsId;
   $('.deleteNewsIcon').on('click', function (e) {
      e.preventDefault();
      newsId = $(this).closest('.news').attr('news-id');
      $('#newsDeleteConfirmationModal').modal('show');
   });
    $('#confirmDeletetingNewsBtn').on('click', function (e) {
        e.preventDefault();
        if(newsId !== null && newsId !== undefined) {
            startLoadingProgress();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/dashboard/deleteNews/' + newsId,
                success: function (data) {
                    successAlert('Poprawnie usunięto aktualność');
                    let newsArea = $('[news-id='+newsId+']');
                    newsArea.remove();
                    stopLoadingProgress();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    let statusCode = xhr.status;
                    let errorMessage;
                    switch (statusCode) {
                        case 404: {
                            // window.location.href = '/status404';
                            break;
                        }
                        default : {
                            // window.location.href = '/status500';
                            break;
                        }
                    }
                    stopLoadingProgress();
                }
            });
        }
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
function startLoadingProgress() {
    $('.loader').css('display', 'block');
}
function stopLoadingProgress() {
    $('.loader').css('display', 'none');
}