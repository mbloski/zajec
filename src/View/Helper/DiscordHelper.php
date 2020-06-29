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
        $this->guildChannels = $this->getView()->get('guildChannels');
        parent::initialize($config);
    }

    private function colorName($user, $prefix = '') {
        if (!$user) {
            return null;
        }

        $name = $user['nick'] ?? $user['user']['username'];
        $color = '';
        foreach ($user['roles'] as $role) {
            if (isset($role['color']) && $role['color'] !== 0) {
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

    public function getChannelById($id, $prop = null) {
        $key = array_search($id, array_column($this->guildChannels, 'id'));
        if ($key === false) {
            return null;
        }

        if (($ret = $this->guildChannels[$key]) && $prop) {
            return Hash::get($ret, $prop);
        }

        return $ret;
    }

    public function resolveEmoji($str, bool $escaped = false) {
        $gt = $escaped? '&gt;' : '>';
        $lt = $escaped? '&lt;' : '<';

        $ret = preg_replace_callback('/'.$lt.'(a?):(\w*):(\d*)'.$gt.'/', function($x) {
            $format = empty($x[1])? 'png' : 'gif';
            return '<img draggable="false" class="emoji" alt=":'.$x[2].':" title=":'.$x[2].':" src="https://cdn.discordapp.com/emojis/'.$x[3].'.'.$format.'">';
        }, $str);

        return $ret;
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
        $str = preg_replace_callback('/\`\`\`((\w*)\n)?\n?(([^\`]|\n)*)\n?\`\`\`\n?/', function($x) { return '<div class="code '.$x[2].'">'.(rtrim(empty($x[3])? $x[2] : $x[3])).'</div>'; }, $str, -1);
        $str = preg_replace_callback('/`([^`]*)`/', function($x) { return '<span class="oneliner ">'.$x[1].'</span>'; }, $str);

        // absolutely kek
        $chunks = [];
        $strr = $str;

        $highlighter = new Highlighter();
        preg_match_all('/<(\w*) class="(\w*) ?(\w*)">/', $str, $otags);

        for ($i = 0; $i < count($otags[0]); ++$i) {
            $class = (isset($otags[3][$i])? $otags[3][$i] : '');

            $type = $otags[2][$i];
            $opentag = '<'.$otags[1][$i].' class="'.$type.' ' .$class. '">';
            $closetag = '</'.$otags[1][$i].'>';

            $o = ['start' => strpos($strr, $opentag), 'end' => strpos($strr, $closetag) + strlen($closetag)];

            $b = substr($strr, 0, $o['start']);
            if (!empty($b)) $chunks[] = ['data' => $b];

            $data = substr($strr, $o['start'] + strlen($opentag), $o['end']);
            $data = substr($data, 0, strpos($data, $closetag));

            if (in_array(strtolower($class), $highlighter->listLanguages())) {
                try {
                    $data = $highlighter->highlight(strtolower($class), html_entity_decode($data, ENT_QUOTES))->value;
                } catch (\Exception $e) {
                    // unknown language, let's just not highlight that
                }
            }
            $chunks[] = ['noparse' => true, 'data' => $data, 'hljs' => $type == 'code' && $class != 'oneliner'];
            $strr = substr($strr, $o['end']);
        }

        if ($strr) {
            $chunks[] = ['data' => $strr];
        }

        if (empty($chunks)) {
            $chunks[0]['data'] = $str;
        }

        $tpl = '';
        foreach ($chunks as $k => $c) {
            if (isset($c['noparse'])) {
                $tpl .= '<code id="'.$k.'" hljs="'.intval($c['hljs']).'">';
            } else {
                $tpl .= preg_replace_callback('/(\&lt;)?((https?|ftp):\/\/[^\s\/$.?#].[^\s]*)/iS', function($x) {
                    $elink = str_replace(['*', '`', '_'], ['\\*', '\\`', '\\_'], $x[2]);
                    if ($x[1] && strrpos($elink, '&gt;') === strlen($elink) - 4) {
                        $elink = substr($elink, 0, -4);
                    }
                    $link = '<a href="'.$elink.'" target="_blank">'.$elink.'</a>';
                    return $link;
                }, $c['data']);
            }

        }

        $ret = $tpl;

        $ret = preg_replace_callback('/(\\\\)([\*_\`])/', function($x) { return '&#'.ord($x[2]).';'; }, $ret);
        $ret = preg_replace_callback('/(\\\\)(\W)/', function($x) { return $x[2]; }, $ret);
        $ret = preg_replace_callback('/\*\*(.*?)\*\*/', function($x) { return '<b>'.$x[1].'</b>'; }, $ret);
        $ret = preg_replace_callback('/\*([^\*]+)\*/', function($x) { return '<i>'.$x[1].'</i>'; }, $ret);
        $ret = preg_replace_callback('/__(.*?)__/', function($x) { return '<u>'.$x[1].'</u>'; }, $ret);
        $ret = preg_replace_callback('/__(.*?)__/', function($x) { return '<u>'.$x[1].'</u>'; }, $ret);
        $ret = preg_replace_callback('/_([^_]*)_\b/', function($x) { return '<i>'.$x[1].'</i>'; }, $ret);
        $ret = preg_replace_callback('/~~(.*?)~~/', function($x) { return '<s>'.$x[1].'</s>'; }, $ret);
        $ret = preg_replace_callback('/\|\|(.*?)\|\|/', function($x) { return '<span class="spoiler">'.$x[1].'</span>'; }, $ret);

        // ...and bring the code blocks back
        $ret = preg_replace_callback('/<code id="(\d*)" hljs="(\d*)">/', function($x) use ($chunks) {
            $ret = $chunks[$x[1]]['data'];
            $class = 'oneliner';
            if ($x[2]) {
                $class = 'hljs';
            }
            return '<div class="'.$class.'">'.$ret.'</div>';;
        }, $ret);

        // it was horrible I wanna go home
        return $ret;
    }

}
