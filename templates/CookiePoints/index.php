<?php $guildMembersNoBots = array_filter($guildMembers, function($x) { return !isset($x['user']['bot']); }); ?>
<table class="pointstable">
    <tr>
        <td class=""></td>
        <?php foreach ($guildMembersNoBots as $i): ?>
            <td class="nick rotate"><div><?= $this->Html->link($this->Discord->getUsernameWithColor($i['user']['id']), ['controller' => 'Stats', 'action' => 'user', $i['user']['id']], ['escape' => false]) ?></div></td>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($guildMembersNoBots as $i): ?>
        <tr class="user-<?= $i['user']['id'] ?>">
            <td class="nick"><?= $this->Html->link($this->Discord->getUsernameWithColor($i['user']['id']), ['controller' => 'Stats', 'action' => 'user', $i['user']['id']], ['escape' => false]) ?></td>
            <?php foreach ($guildMembersNoBots as $j): ?>
                <?php
                $color = '';
                $count = '';
                if ($p = ($cookiePoints[$i['user']['id']][$j['user']['id']] ?? null)) {
                    $count = $p->count;
                    $color = $this->Color->rgbify($count, -$maxRep, $maxRep);
                }
                if ($i['user']['id'] == $j['user']['id']) {
                    $color = '000';
                }
                ?>
                <td class="point" style="background-color:#<?= $color ?>;"><?= $count ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
