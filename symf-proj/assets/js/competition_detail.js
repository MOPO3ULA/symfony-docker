import WaveSurfer from "wavesurfer.js";

$(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$('#sample-modal-form').submit(function (e) {
    e.preventDefault();

    let postLink = $('#original-post').attr('href');

    let form = $('#sample-modal-form');
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
                $('#modal-submit').modal('hide');
            } else {
                let errorDiv = $('#error');
                errorDiv.removeClass('is-hidden');
                errorDiv.text(response.error);
            }
        },
        error: function (xhr, status, error) {
            $('#error').text(error);
        }
    })
});

let waveColor = '#80bae0';
let progressColor = '#6b6fbd';
let stopColor = 'gray';

let sampleLink = $('audio').children().attr('src');

let surfer = WaveSurfer.create({
    container: '#waveform',
    waveColor: waveColor,
    progressColor: progressColor,
    normalize: true,
    pixelRatio: 1
});

surfer.load(sampleLink);

surfer.on('pause', function () {
    surfer.setProgressColor(stopColor);
    $('#wave-player').removeClass('fa-pause').addClass('fa-play');
});

surfer.on('play', function () {
    surfer.setProgressColor(progressColor);
    $('#wave-player').removeClass('fa-play').addClass('fa-pause');
});

$("#wave-player").on('click', function () {
    if (surfer.isPlaying()) {
        surfer.pause();
    } else {
        surfer.play();
    }
});