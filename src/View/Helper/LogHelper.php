<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Calendar helper
 */
class LogHelper extends Helper
{
    public $helpers = ['Html', 'Discord'];

    public function chat($guildMembers, $log)
    {
        echo '<div class="msg">';
        $nick = $this->Discord->getUsernameWithColor($guildMembers, $log->author_id) ?? $log->author_id;
        $line = $this->Discord->resolveNickname($guildMembers, $this->Discord->resolveEmoji($log->message));
        $line = str_replace("\n", "\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $line);

        if ($log->has('attachments')) {
            if ($line) {
                $line .= ' ';
            }
            $line .= implode(' ', array_map(function($x) { return $this->Html->link(basename($x->url), $x->url, ['target' => '_blank']); }, $log->attachments));
        }

        if ($log->has('edit_history') && !empty($log->edit_history)) {

            if ($line) {
                $line .= $this->Html->link('<small><cite>(show edit history)</cite></small>', '#hide%23'.'c'.$log->message_id, ['escape' => false, 'class' => 'hide', 'id' => 'hide%23'.'c'.$log->message_id]);
                $line .= $this->Html->link('<small><cite>(hide edit history)</cite></small>', '#show%23'.'c'.$log->message_id, ['escape' => false, 'class' => 'show', 'id' => 'show%23'.'c'.$log->message_id]);
                $line .= '<span class="collapsible">'.implode('', array_map(function($x) { return '<br>&nbsp;<a>OLD:</a> '.$x->message; }, $log->edit_history)).'</span>';
            }
        }

        echo <<<EOL
            <a href="#$log->message_id" id="$log->message_id">{$log->created->format('H:i')}</a>
            <<span class="nickname">{$nick}</span>>
            <pre>{$line}</pre>
            <br>
EOL;
        echo '</div>';
    }

    public function status($line) {
        $line = h($line);
        $anchor = crc32($line);
        $now = date('H:i');
        echo <<<EOL
        <div class="info">
            <a href="#$anchor" id="$anchor">$now</a>
            <pre>$line</pre>
        </div>
EOL;
    }

    public function resolveLinks($str) {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,5}(\/\S*\w)?/";
        $urls = array();
        $urlsToReplace = array();
        if(preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = count($urls[0]);
            for($i=0; $i<$numOfMatches; $i++) {
                $alreadyAdded = false;
                $numOfUrlsToReplace = count($urlsToReplace);
                for($j=0; $j<$numOfUrlsToReplace; $j++) {
                    if($urlsToReplace[$j] == $urls[0][$i]) {
                        $alreadyAdded = true;
                    }
                }
                if(!$alreadyAdded) {
                    array_push($urlsToReplace, $urls[0][$i]);
                }
            }
            $numOfUrlsToReplace = count($urlsToReplace);
            for($i=0; $i<$numOfUrlsToReplace; $i++) {
                $str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target=\"_blank\">".$urlsToReplace[$i]."</a>", $str);
            }
            return $str;
        }
        return $str;
    }
}
