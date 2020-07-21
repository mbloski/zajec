<?php $guildMembersNoBots = array_filter($guildMembers, function($x) { return !isset($x['user']['bot']); }); ?>
<table class="pointstable">
    <tr>
        <td class=""></td>
        <?php foreach ($guildMembersNoBots as $i): ?>
            <td class="nick rotate"><div><?= $i['user']['username'] ?></div></td>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($guildMembersNoBots as $i): ?>
        <tr>
            <td class="nick"><?= $i['user']['username'] ?></td>
            <?php foreach ($guildMembersNoBots as $j): ?>
                <?php
                $color = '';
                $count = '';
                if ($i['user']['id'] == $j['user']['id']) {
                    $color = '000';
                }
                if ($p = ($cookiePoints[$i['user']['id']][$j['user']['id']] ?? null)) {
                    $count = $p->count;
                    $color = $this->Color->rgbify($count, -$maxRep, $maxRep);
                }
                ?>
                <td class="point" style="background-color:#<?= $color ?>;"><?= $count ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
