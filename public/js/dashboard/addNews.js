$(document).ready(function () {
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imageUploaded').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#news_file").on('change', function(e) {
        e.preventDefault();
        $('.card').remove();
        let content =
            '<div class="card">' +
            '<i class="fa fa-trash deleteImageIcon"></i>' +
            '<div class="card-body">' +
            '<img id="imageUploaded" class="img-fluid rounded mx-auto d-block">' +
            '</div>' +
            '</div>';
        $('.imageArea').append(content);
        readURL(this);
    });

    $('body').on('click', '.deleteImageIcon', function (e) {
        e.stopPropagation();
    });
});