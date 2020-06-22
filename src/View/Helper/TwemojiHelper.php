<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Cache\Cache;
use Cake\View\Helper;

final class TwemojiHelper extends Helper
{
    private $emojis;

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $format = $config['format'] ?? 'svg';
        $version = $config['version'] ?? 'v2';

        $response = $this->requestRepository();

        $emojis = [];
        foreach ($response['tree'] as $element) {
            if (!preg_match('#^assets/svg/([0-9a-z-]+).svg$#', $element['path'], $matches)) {
                continue;
            }
            $emoji = '';

            // ® © ™
            if (strlen($matches[1]) == 2 || $matches[1] == '2122') {
                continue;
            }

            $splitmoji = explode('-', $matches[1]);

            // https://github.com/twitter/twemoji/issues/272
            if (count($splitmoji) === 2 && $splitmoji[1] == '20e3') {
                $splitmoji = [$splitmoji[0], 'fe0f', '20e3'];
            }

            foreach ($splitmoji as $code) {
                $emoji .= mb_chr(hexdec($code));
            }
            $emojis[$emoji] = sprintf(
                '<img draggable="false" class="emoji" alt="%s" src="https://abs.twimg.com/emoji/%s/%s/%s.%s">',
                $emoji,
                $version,
                $format,
                $matches[1],
                $format
            );
        }

        $this->emojis = $emojis;
    }

    public function replace(string $string): string
    {
        return strtr($string, $this->emojis);
    }

    private function requestRepository() {
        $ret = Cache::read('twemoji', 'long');
        if (!$ret) {
            $ch = curl_init('https://api.github.com/repos/twitter/twemoji/git/trees/master?recursive=1');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: ZAJEC']);
            $ret = json_decode(curl_exec($ch), true);
            Cache::write('twemoji', $ret, 'long');
        }

        return $ret;
    }
}
