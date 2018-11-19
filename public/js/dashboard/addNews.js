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
        console.log('weszlo');
        $('.card').remove();
        let content =
            '<div class="card">' +
            '<div class="card-body">' +
            '<img id="imageUploaded" class="img-fluid rounded mx-auto d-block">' +
            '</div>' +
            '</div>';
        $('.imageArea').append(content);
        readURL(this);
    });
});