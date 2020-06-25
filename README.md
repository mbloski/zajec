# ZAJEC discord stats

## Installation
1. `composer install`
2. `cp config/discord_example.php config/discord.php`

NOTE: muffin/oauth2 is broken. Replace `$event->result` with `$event->getResult()` in `OAuthAuthenticate.php`.

NOTE: masterexploder/phpthumb is broken. Define `$this->options` in GD.php's `setOptions()`.
