<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\View;
use Highlight\Highlighter;

/**
 * Discord helper
 */
class DiscordHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    private $guildMembers;

    public function initialize(array $config): void
    {
        $this->guildMembers = $this->getView()->get('guildMembers');
        parent::initialize($config);
    }

    private function colorName($user, $prefix = '') {
        if (!$user) {
            return null;
        }

        $name = $user['nick'] ?? $user['user']['username'];
        $color = '';
        foreach ($user['roles'] as $role) {
            if ($role['color'] !== 0) {
                $color = 'color:#'.str_pad(dechex($role['color']), 6, '0', STR_PAD_LEFT).';';
            }
        }
        return '<span style="font-weight: bold;'.$color.'">'.h($prefix.$name).'</span>';
    }

    public function getUserById($id, $prop = null) {
        $key = array_search($id, Hash::extract($this->guildMembers, '{n}.user.id'));

        if ($key === false) {
            return null;
        }

        if (($ret = $this->guildMembers[$key]) && $prop) {
            return Hash::get($ret, $prop);
        }

        return $ret;
    }

    public function getUsernameWithColor($id) {
        $ret = $this->getUserById($id);
        return $this->colorName($ret);
    }

    public function resolveEmoji($str, bool $escaped = false) {
        $gt = $escaped? '&gt;' : '>';
        $lt = $escaped? '&lt;' : '<';

        return preg_replace('/'.$lt.'\:(\w*)\:(\d*)'.$gt.'/', '<img draggable="false" class="emoji" alt=":$1:" title=":$1:" src="https://cdn.discordapp.com/emojis/$2.png">', $str);
    }

    public function resolveNickname($str) {
        return preg_replace_callback('/&lt;@!?(\d*)&gt;/', function($x) {
            $user = $this->getUserById($x[1]);
            if (!$user) {
                return '@'.$x[1];
            }

            return $this->colorName($user, '@');
        }, $str);
    }

    function resolveMarkdown($str) {
        // TODO: write a proper parser
        $c = 0;
        $str = preg_replace_callback('/\`\`\`((\w*)\n)?\n?(([^\`]|\n)*)\n?\`\`\`\n?/', function($x) { return '<div class="code '.$x[2].'">'.(rtrim(empty($x[3])? $x[2] : $x[3])).'</div>'; }, $str, -1, $c);
        $str = preg_replace_callback('/`([^`]*)`/', function($x) { return '<div class="code oneliner">'.$x[1].'</div>'; }, $str);

        // absolutely kek
        $chunks = [];
        $strr = $str;

        if ($c > 0) {
            $highlighter = new Highlighter();
            preg_match_all('/<div class="(\w*) ?(\w*)">/', $str, $otags);

            for ($i = 0; $i < count($otags[0]); ++$i) {
                $class = (isset($otags[2][$i])? $otags[2][$i] : '');

                $type = $otags[1][$i];
                $opentag = '<div class="'.$type.' ' .$class. '">';
                $closetag = '</div>';


                $o = ['start' => strpos($strr, $opentag), 'end' => strpos($strr, $closetag) + strlen($closetag)];

                $b = substr($strr, 0, $o['start']);
                if (!empty($b))  $chunks[] = ['data' => $b];
                $data = substr($strr, $o['start'] + strlen($opentag), $o['end'] - strlen($b) - strlen($opentag) - strlen($closetag));

                if ($type == 'code' && in_array(strtolower($class), $highlighter->listLanguages())) {
                    try {
                        $data = $highlighter->highlight(strtolower($class), html_entity_decode($data, ENT_QUOTES))->value;
                    } catch (\Exception $e) {
                        // unknown language, let's just not highlight that
                    }
                }
                $chunks[] = ['noparse' => true, 'data' => $data];
                $strr = substr($strr, $o['end']);
            }
        }

        if ($strr) {
            if (!empty($b))  $chunks[] = ['data' => $strr];
        }

        if (empty($chunks)) {
            $chunks[0]['data'] = $str;
        }

        $tpl = '';
        foreach ($chunks as $k => $c) {
            if (isset($c['noparse'])) {
                $tpl .= '<code id="'.$k.'">';
            } else {
                $c['data'] = preg_replace_callback('/(\&lt;)?((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,5}(\/\S*\w)?)/', function($x) { return '<span class="link">'.$x[0].'</span>'; }, $c['data']);
                $tpl .= preg_replace_callback('/<span class="link">(.*)<\/span>/', function($x) { return preg_replace('/([\*\_\`])/', "\\\\$1", html_entity_decode($x[1])); }, $c['data']);
            }

        }

        $ret = $tpl;

        $ret = preg_replace_callback('/(\\\\)([\*_\`])/', function($x) { return '&#'.ord($x[2]).';'; }, $ret);
        $ret = preg_replace_callback('/(\\\\)(\W)/', function($x) { return $x[2]; }, $ret);
        $ret = preg_replace_callback('/\*\*(.*?)\*\*/', function($x) { return '<b>'.$x[1].'</b>'; }, $ret);
        $ret = preg_replace_callback('/\*([^\*]+)\*/', function($x) { return '<i>'.$x[1].'</i>'; }, $ret);
        $ret = preg_replace_callback('/__(.*?)__/', function($x) { return '<u>'.$x[1].'</u>'; }, $ret);
        $ret = preg_replace_callback('/__(.*?)__/', function($x) { return '<u>'.$x[1].'</u>'; }, $ret);
        $ret = preg_replace_callback('/_([^_]*)_/', function($x) { return '<i>'.$x[1].'</i>'; }, $ret);
        $ret = preg_replace_callback('/\|\|(.*?)\|\|/', function($x) { return '<span class="spoiler">'.$x[1].'</span>'; }, $ret);

        // ...and bring the code blocks back
        $ret = preg_replace_callback('/<code id="(\d*)">/', function($x) use ($chunks) { return '<div class="hljs">'.$chunks[$x[1]]['data'].'</div>'; }, $ret);

        // it was horrible I wanna go home
        return $ret;
    }

}
