<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;

/**
 * Calendar helper
 */
class LogHelper extends Helper
{
    public $helpers = ['Html', 'Url', 'Discord', 'Twemoji'];

    public function chat($log, $fullDate = false)
    {
        echo '<div class="msg">';
        if ($log->deleted) {
            echo $this->Html->link('<small><cite>(show deleted message)</cite></small>', '#hide%23'.'d'.$log->message_id, ['escape' => false, 'class' => 'hide', 'id' => 'hide%23'.'d'.$log->message_id]);
        }

        echo '<div class="'.($log->deleted? 'collapsible' : '').'">';

        $nick = $this->Html->link($this->Discord->getUsernameWithColor($log->author_id) ?? $log->author_id, ['controller' => 'Stats', 'action' => 'user', $log->author_id], ['class' => 'userlink', 'escape' => false]);
        $line = $this->richLine($log->message);

        if ($log->has('attachments') && $log->attachments) {
            if ($line) {
                $line .= ' ';
            }
            $line .= implode(' ', array_map(function($x) { return $this->Html->link('['.basename($x->url).']', $x->url, ['target' => '_blank']); }, $log->attachments));
        }

        if ($log->has('edit_history') && !empty($log->edit_history)) {
            if ($line) {
                $line .= ' ';
                $line .= $this->Html->link('<small><cite>(show edit history)</cite></small>', '#hide%23'.'c'.$log->message_id, ['escape' => false, 'class' => 'hide', 'id' => 'hide%23'.'c'.$log->message_id]);
                $line .= $this->Html->link('<small><cite>(hide edit history)</cite></small>', '#show%23'.'c'.$log->message_id, ['escape' => false, 'class' => 'show', 'id' => 'show%23'.'c'.$log->message_id]);
                $line .= '<span class="collapsible">'.implode('', array_map(function($x) { return '<br>&nbsp;<a>OLD:</a> '.$this->richLine($x->message); }, $log->edit_history)).'</span>';
            }
        }

        $format = 'H:i';
        if ($fullDate) {
            $format = 'd.m.Y '.$format;
        }

        $queryParams = Router::getRequest()->getQueryParams();
        $queryParams['date'] = $log->created->format('Y-m-d');
        if (isset($queryParams['search'])) {
            unset($queryParams['search']);
        }

        $anchor = $this->Html->link($log->created->format($format), $this->Url->build(['?' => $queryParams]).'#'.$log->message_id, ['class' => 'anchor', 'id' => $log->message_id, 'escape' => false]);
        $anchor .= '<div class="anchor-marker"></div>';

        echo <<<EOL
            <div class="timestamp">{$anchor}</div>
            <div class="logline">&lt;<span class="nickname">{$nick}</span>&gt; <span class="rich-line">{$line}</span></div>
EOL;
        echo '</div>';
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
        $ret = $str;
        $str = strip_tags($str) ?? '';
        $reg_exUrl = "/(\&lt;)?((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,5}(\/\S*\w)?)/";
        $urls = array();
        $urlsToReplace = array();
        if(preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = count($urls[2]);
            for($i=0; $i<$numOfMatches; $i++) {
                if ($urls[1][$i] === '&lt;' && strrpos($urls[2][$i], '&gt') === strlen($urls[2][$i]) - 3) {
                    $urls[2][$i] = substr($urls[2][$i], 0, -3);
                }

                $alreadyAdded = false;
                $numOfUrlsToReplace = count($urlsToReplace);
                for($j=0; $j<$numOfUrlsToReplace; $j++) {
                    if($urlsToReplace[$j] == $urls[2][$i]) {
                        $alreadyAdded = true;
                    }
                }
                if(!$alreadyAdded) {
                    array_push($urlsToReplace, $urls[2][$i]);
                }
            }
            $numOfUrlsToReplace = count($urlsToReplace);
            for($i=0; $i<$numOfUrlsToReplace; $i++) {
                $ret = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target=\"_blank\">".$urlsToReplace[$i]."</a>", $ret);
            }
            return $ret;
        }
        return $ret;
    }

    public function richLine($str, $escape = true) {
        if ($escape) {
            $str = h($str);
        }

        return $this->Discord->resolveNickname($this->Discord->resolveEmoji($this->Twemoji->replace($this->resolveLinks($this->Discord->resolveMarkdown($str))), true));
    }
}
