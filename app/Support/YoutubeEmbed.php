<?php

namespace App\Support;

class YoutubeEmbed
{
    public static function fromUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);

        if (preg_match('~(?:youtube\.com/embed/|youtube-nocookie\.com/embed/)([a-zA-Z0-9_-]{11})~', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('~(?:youtube\.com/watch\?v=|youtube\.com/shorts/|youtu\.be/)([a-zA-Z0-9_-]{11})~', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('~^[a-zA-Z0-9_-]{11}$~', $url)) {
            return 'https://www.youtube.com/embed/'.$url;
        }

        return null;
    }
}
