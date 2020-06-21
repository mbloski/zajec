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

    public function resolveEmoji($str, int $h = 16, bool $escaped = false) {
        $gt = $escaped? '&gt;' : '>';
        $lt = $escaped? '&lt;' : '<';

        return preg_replace('/'.$lt.'\:(\w*)\:(\d*)'.$gt.'/', '<img style="height:'.$h.'px;" class="emoji" alt=":$1:" title=":$1:" src="https://cdn.discordapp.com/emojis/$2.png">', $str);
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
}
