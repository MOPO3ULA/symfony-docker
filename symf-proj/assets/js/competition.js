$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$('.competition-form').each(function (index) {
    let ind = index + 1;

    $('#sample-modal-form-'+ind).submit(function (e) {
        e.preventDefault();

        let form = $('#sample-modal-form-'+ind);
        let fd = new FormData(form[0]);

        $.ajax({
            type: 'POST',
            url: '/competition/submit',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            data: fd,
            success: function (response) {

            },
            error: function (response) {

            }
        })
    });
});