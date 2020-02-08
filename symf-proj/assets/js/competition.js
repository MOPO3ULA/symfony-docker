$(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$('.competition-form').each(function (index) {
    let ind = index + 1;

    $('#sample-modal-form-' + ind).submit(function (e) {
        e.preventDefault();

        let postLink = $('#original-post-' + ind).attr('href');

        let form = $('#sample-modal-form-' + ind);
        let fd = new FormData(form[0]);

        fd.append('postLink', postLink);

        $.ajax({
            type: 'POST',
            url: '/competition/submit',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            data: fd,
            success: function (response) {
                if (response.status === 'success') {
                    $('#modal-submit-' + ind).modal('hide');
                } else {
                    let errorDiv = $('#error-' + ind);
                    errorDiv.removeClass('is-hidden');
                    errorDiv.text(response.error);
                }
            },
            error: function (xhr, status, error) {
                $('#error-' + ind).text(error);
            }
        })
    });
});