<?php
declare(strict_types=1);

namespace App\Controller\Component;

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
        return curl_exec($ch);
    }

    public function getGuild($id) {
        return $this->genericGet('/guilds/'.$id);
    }

    public function getGuildMembers($id) {
        return $this->genericGet('/guilds/'.$id.'/members?limit=1000');
    }

}
