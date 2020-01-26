import WaveSurfer from "wavesurfer.js";

function padNum(num) {
    return num.toString().padStart(2,0);
}

let waveColor = '#80bae0';
let progressColor = '#6b6fbd';
let stopColor = 'gray';

var wavesurfer = WaveSurfer.create({
    container: '#waveform',
    waveColor: waveColor,
    progressColor: progressColor,
    normalize: true,
    pixelRatio: 1
});

let sampleSrc = $('audio').children().first().attr('src');

wavesurfer.load(
    'https://cors-anywhere.herokuapp.com/' + sampleSrc
);

wavesurfer.on('pause', function () {
    wavesurfer.setProgressColor(stopColor);
    $('#wave-player').removeClass('fa-pause').addClass('fa-play');
});

wavesurfer.on('play', function () {
    wavesurfer.setProgressColor(progressColor);
    $('#wave-player').removeClass('fa-play').addClass('fa-pause');
});

$(".js-btn-play").on('click', function (event) {
    if (wavesurfer.isPlaying()) {
        wavesurfer.pause();
    } else {
        wavesurfer.play();
    }
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
