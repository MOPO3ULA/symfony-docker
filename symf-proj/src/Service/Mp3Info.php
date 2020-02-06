<?php

namespace App\Service;

class Mp3Info
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public static function formatTime($duration) //as hh:mm:ss
    {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = $duration - ($hours * 3600) - ($minutes * 60);
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    //Read first mp3 frame only...  use for CBR constant bit rate MP3s
    public function getDurationEstimate()
    {
        return $this->getDuration($use_cbr_estimate = true);
    }

    //Read entire file, frame by frame... ie: Variable Bit Rate (VBR)
    public function getDuration($use_cbr_estimate = false)
    {
        $fd = fopen($this->filename, "rb");

        $duration = 0;
        $block = fread($fd, 100);
        $offset = $this->skipID3v2Tag($block);
        fseek($fd, $offset, SEEK_SET);
        while (!feof($fd)) {
            $block = fread($fd, 10);
            if (strlen($block) < 10) {
                break;
            }

            if ($block[0] == "\xff" && (ord($block[1]) & 0xe0)) {
                $info = self::parseFrameHeader(substr($block, 0, 4));
                if (empty($info['Framesize'])) {
                    return $duration;
                } //some corrupt mp3 files
                fseek($fd, $info['Framesize'] - 10, SEEK_CUR);
                $duration += ($info['Samples'] / $info['Sampling Rate']);
            } else if (strpos($block, 'TAG') === 0) {
                fseek($fd, 128 - 10, SEEK_CUR);//skip over id3v1 tag size
            } else {
                fseek($fd, -9, SEEK_CUR);
            }
            if ($use_cbr_estimate && !empty($info)) {
                return $this->estimateDuration($info['Bitrate'], $offset);
            }
        }
        return round($duration);
    }

    private function estimateDuration($bitrate, $offset)
    {
        $kbps = ($bitrate * 1000) / 8;
        $datasize = filesize($this->filename) - $offset;
        return round($datasize / $kbps);
    }

    private function skipID3v2Tag(&$block)
    {
        if (strpos($block, "ID3") === 0) {
            $id3v2_flags = ord($block[5]);
            $flag_footer_present = ($id3v2_flags & 0x10) ? 1 : 0;
            $z0 = ord($block[6]);
            $z1 = ord($block[7]);
            $z2 = ord($block[8]);
            $z3 = ord($block[9]);
            if ((($z0 & 0x80) == 0) && (($z1 & 0x80) == 0) && (($z2 & 0x80) == 0) && (($z3 & 0x80) == 0)) {
                $header_size = 10;
                $tag_size = (($z0 & 0x7f) * 2097152) + (($z1 & 0x7f) * 16384) + (($z2 & 0x7f) * 128) + ($z3 & 0x7f);
                $footer_size = $flag_footer_present ? 10 : 0;
                return $header_size + $tag_size + $footer_size;//bytes to skip
            }
        }
        return 0;
    }

    public static function parseFrameHeader($fourbytes)
    {
        static $versions = [
            0x0 => '2.5', 0x1 => 'x', 0x2 => '2', 0x3 => '1', // x=>'reserved'
        ];
        static $layers = [
            0x0 => 'x', 0x1 => '3', 0x2 => '2', 0x3 => '1', // x=>'reserved'
        ];
        static $bitrates = [
            'V1L1' => [0, 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448],
            'V1L2' => [0, 32, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 384],
            'V1L3' => [0, 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320],
            'V2L1' => [0, 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256],
            'V2L2' => [0, 8, 16, 24, 32, 40, 48, 56, 64, 80, 96, 112, 128, 144, 160],
            'V2L3' => [0, 8, 16, 24, 32, 40, 48, 56, 64, 80, 96, 112, 128, 144, 160],
        ];
        static $sample_rates = [
            '1' => [44100, 48000, 32000],
            '2' => [22050, 24000, 16000],
            '2.5' => [11025, 12000, 8000],
        ];
        static $samples = [
            1 => [1 => 384, 2 => 1152, 3 => 1152,], //MPEGv1,     Layers 1,2,3
            2 => [1 => 384, 2 => 1152, 3 => 576,], //MPEGv2/2.5, Layers 1,2,3
        ];

        $b1 = ord($fourbytes[1]);
        $b2 = ord($fourbytes[2]);

        $version_bits = ($b1 & 0x18) >> 3;
        $version = $versions[$version_bits];
        $simple_version = ($version == '2.5' ? 2 : $version);

        $layer_bits = ($b1 & 0x06) >> 1;
        $layer = $layers[$layer_bits];

        $bitrate_key = sprintf('V%dL%d', $simple_version, $layer);
        $bitrate_idx = ($b2 & 0xf0) >> 4;
        $bitrate = $bitrates[$bitrate_key][$bitrate_idx] ?? 0;

        $sample_rate_idx = ($b2 & 0x0c) >> 2;//0xc => b1100
        $sample_rate = isset($sample_rates[$version][$sample_rate_idx]) ? $sample_rates[$version][$sample_rate_idx] : 0;
        $padding_bit = ($b2 & 0x02) >> 1;

        $info = [];
        $info['Version'] = $version;
        $info['Layer'] = $layer;
        $info['Bitrate'] = $bitrate;
        $info['Sampling Rate'] = $sample_rate;
        $info['Framesize'] = self::framesize($layer, $bitrate, $sample_rate, $padding_bit);
        $info['Samples'] = $samples[$simple_version][$layer];
        return $info;
    }

    private static function framesize($layer, $bitrate, $sample_rate, $padding_bit)
    {
        if ($layer == 1)
            return (int)(((12 * $bitrate * 1000 / $sample_rate) + $padding_bit) * 4);
        else //layer 2, 3
            return (int)(((144 * $bitrate * 1000) / $sample_rate) + $padding_bit);
    }
}