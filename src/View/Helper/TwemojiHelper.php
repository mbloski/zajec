<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Cache\Cache;
use Cake\View\Helper;

final class TwemojiHelper extends Helper
{
    private $emojis;
    private $img_format = '<img draggable="false" class="emoji" alt="%s" src="https://abs.twimg.com/emoji/%s/%s/%s.%s">';

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->format = $config['format'] ?? 'svg';
        $this->version = $config['version'] ?? 'v2';

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
                $this->img_format,
                $emoji,
                $this->version,
                $this->format,
                $matches[1],
                $this->format
            );
        }

        $this->emojis = $emojis;
        $this->populateAnnotations();
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

    private function populateAnnotations() {
        $data = json_decode(file_get_contents(RESOURCES . 'emoji_annotations.json'), true);

        $appendMojis = function($name, $surrogates) {
            // ® © ™
            if (mb_strlen($surrogates) == 2 || $surrogates[1] == 0x2122) {
                return;
            }

            // https://github.com/twitter/twemoji/issues/272
            if (mb_strlen($surrogates) == 2) {
                $surrogates = join('', array_filter(mb_str_split($surrogates), function ($x) {
                    return mb_ord($x) !== 0xFE0F;
                }));
            }

            $this->emojis[':'.$name.':'] = $this->emojis[$surrogates];
        };

        foreach ($data as $emojiGroup) {
            foreach ($emojiGroup as $emoji) {
                if (isset($emoji['diversityChildren'])) {
                    foreach ($emoji['diversityChildren'] as $diverseEmoji) {
                        foreach ($diverseEmoji['names'] as $name) {
                            $appendMojis($name, $emoji['surrogates']);
                        }
                    }
                }

                foreach ($emoji['names'] as $name) {
                    $appendMojis($name, $emoji['surrogates']);
                }
            }
        }
    }
}
