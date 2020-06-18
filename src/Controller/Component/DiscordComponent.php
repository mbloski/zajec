<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Discord component
 */
class DiscordComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    private $token;

    public function initialize(array $config): void
    {
        if (!array_key_exists('token', $config)) {
            throw new \Exception('DiscordComponent requires Token');
        }

        parent::initialize($config);
        $this->token = $config['token'];
        $this->url = 'https://discordapp.com/api/v6/';
    }

    private function genericGet($url) {
        $request_headers = [];
        $request_headers[] = 'authorization: Bot '.$this->token;

        $ch = curl_init($this->url.$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return json_decode(curl_exec($ch), true);
    }

    public function getGuild($id) {
        $ret = Cache::read('discord_guilds');
        if (!$ret) {
            $ret = $this->genericGet('/guilds/'.$id);
            Cache::write('discord_guilds', $ret);
        }
        return $ret;
    }

    public function getRoles($guildId) {
        $ret = Cache::read('discord_guild_roles');
        if (!$ret) {
            $ret = $this->genericGet('/guilds/' . $guildId . '/roles');
            Cache::write('discord_guild_roles', $ret);
        }
        return $ret;
    }

    public function getChannels($guildId) {
        $ret = Cache::read('discord_guild_channels');
        if (!$ret) {
            $ret = $this->genericGet('/guilds/' . $guildId . '/channels');
            Cache::write('discord_guild_channels', $ret);
        }
        return $ret;
    }

    public function getGuildMembers($id) {
        $ret = Cache::read('discord_guild_members');
        if (!$ret) {
            $ret = $this->genericGet('/guilds/' . $id . '/members?limit=1000');
            Cache::write('discord_guild_members', $ret);
        }
        return $ret;
    }

    public function getGuildMembersWithRoles($id) {
        $ret = $this->getGuildMembers($id);
        $roles = $this->getRoles($id);
        foreach ($ret as &$member) {
            foreach ($member['roles'] as &$role) {
                $role = ['id' => $role];
                $key = array_search($role['id'], array_column($roles, 'id'));
                if ($key) {
                    $role = $roles[$key];
                }
            }

        }

        return $ret;
    }

}
