$(document).ready(function () {
    $('#upload-input').change(function () {
        $("#upload-form").submit();
    });

    $('.upload-main-btn').click(function () {
        $('#upload-input').click();
    });
});