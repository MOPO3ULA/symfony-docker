import WaveSurfer from "wavesurfer.js";

/*
function padNum(num) {
    return num.toString().padStart(2, 0);
}
*/

let waveColor = '#80bae0';
let progressColor = '#6b6fbd';
let stopColor = 'gray';

let sampleLinks = $('audio').children();

$('div.card').each(function (index) {
    let idIndex = index + 1;
    let surfer = window['wavesurfer' + idIndex];

    surfer = WaveSurfer.create({
        container: '#waveform-' + idIndex,
        waveColor: waveColor,
        progressColor: progressColor,
        normalize: true,
        pixelRatio: 1
    });

    surfer.load(sampleLinks[index].src);

    surfer.on('pause', function () {
        surfer.setProgressColor(stopColor);
        $('#wave-player-'+idIndex).removeClass('fa-pause').addClass('fa-play');
    });

    surfer.on('play', function () {
        surfer.setProgressColor(progressColor);
        $('#wave-player-'+idIndex).removeClass('fa-play').addClass('fa-pause');
    });


    $("#wave-player-"+idIndex).on('click', function (event) {
        if (surfer.isPlaying()) {
            surfer.pause();
        } else {
            surfer.play();
        }
    });
});

/*
wavesurfer.on('audioprocess', function() {
    if(wavesurfer.isPlaying()) {
        let currentTime = wavesurfer.getCurrentTime();

        document.getElementById('time-current').innerText =
            padNum(Math.floor(currentTime / 60)) + ':' +
            padNum(Math.ceil(currentTime % 60));
    }
});
*/
