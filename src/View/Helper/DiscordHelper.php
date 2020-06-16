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

    public function getUserById($guildMembers, $id, $prop = null) {
        $key = array_search($id, Hash::extract($guildMembers, '{n}.user.id'));
        if (!$key) {
            return null;
        }

        if (($ret = $guildMembers[$key]) && $prop) {
            return Hash::get($ret, $prop);
        }

        return $ret;
    }

    public function resolveEmoji($str, $w = 16, $h = 16) {
        $wh = '';
        if ($w) {
            $wh .= ' width="'.$w.'"';
        }
        if ($h) {
            $wh .= ' height="'.$h.'"';
        }
        return preg_replace('/&lt;\:(\w*)\:(\d*)&gt;/', '<img alt=":$1:" title=":$1:" src="https://cdn.discordapp.com/emojis/$2.png"'.$wh.'>', htmlspecialchars($str));
    }

    public function resolveNickname($guildMembers, $str) {
        return preg_replace_callback('/&lt;@!?(\d*)&gt;/', function($x) use ($guildMembers) {
            $user = $this->getUserById($guildMembers, $x[1]);
            if (!$user) {
                return '@'.$x[1];
            }

            $name = $user['nick'] ?? $user['user']['username'];
            return '@'.$name;
        }, $str);
    }

}
