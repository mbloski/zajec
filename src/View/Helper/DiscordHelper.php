<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use Cake\View\View;

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
        $str = preg_replace_callback('/\`\`\`(\w*\n)?\n?(([^\`]|\n)*)\n?\`\`\`/', function($x) { return '<div class="code">'.(rtrim(empty($x[2])? $x[1] : $x[2])).'</div>'; }, $str, -1, $c);

        // absolutely kek
        $chunks = [];
        $strr = $str;
        for ($i = 0; $i < $c; ++$i) {
            $o = ['start' => strpos($strr, '<div class="code">'), 'end' => strpos($strr, '</div>') + 6];
            $b = substr($strr, 0, $o['start']);
            if (!empty($b))  $chunks[] = ['data' => $b];
            $chunks[] = ['noparse' => true, 'data' => substr($strr, $o['start'], $o['end'] - strlen($b))];
            $strr = substr($strr, $o['end']);
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
                $tpl .= $c['data'];
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
        $ret = preg_replace_callback('/`([^`]*)`/', function($x) { return '<span class="oneliner">'.$x[1].'</span>'; }, $ret);

        // ...and bring the code blocks back
        $ret = preg_replace_callback('/<code id="(\d*)">/', function($x) use ($chunks) { return $chunks[$x[1]]['data']; }, $ret);

        // it was horrible I wanna go home
        return $ret;
    }

}
