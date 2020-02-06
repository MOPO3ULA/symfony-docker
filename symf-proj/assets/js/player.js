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
let cards = $('div.card');
let countOfCards = cards.length;

cards.each(function (index) {
    let idIndex = index + 1;

    let surfer = WaveSurfer.create({
        container: '#waveform-' + idIndex,
        waveColor: waveColor,
        progressColor: progressColor,
        normalize: true,
        pixelRatio: 1
    });

    window['wavesurfer' + idIndex] = surfer;

    surfer.load(sampleLinks[index].src);

    surfer.on('pause', function () {
        surfer.setProgressColor(stopColor);
        $('#wave-player-' + idIndex).removeClass('fa-pause').addClass('fa-play');
    });

    surfer.on('play', function () {
        stopOtherTracks(idIndex);

        surfer.setProgressColor(progressColor);
        $('#wave-player-' + idIndex).removeClass('fa-play').addClass('fa-pause');
    });

    $("#wave-player-" + idIndex).on('click', function () {
        if (surfer.isPlaying()) {
            surfer.pause();
        } else {
            surfer.play();
        }
    });
});

/**
 * Останавливаем все активные треки, кроме текущего
 * @param ind
 */
function stopOtherTracks(ind) {
    for (let i = 1; i <= countOfCards; ++i) {
        if (ind !== i) {
            if (window['wavesurfer' + i].isPlaying()) {
                window['wavesurfer' + i].pause();
            }
        }
    }
}

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
