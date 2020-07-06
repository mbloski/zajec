<div class="sbox">
    <h2 class="text-center">
        Top channels
    </h2>
    <table class="itemtable">
        <thead>
        <th>#</th>
        <th>Channel</th>
        <th>Lines</th>
        <th>Most active</th>
        <th>Random quote</th>
        </thead>
        <tbody>
        <?php foreach ($topChannels as $n => $channel): ?>
            <?php $key = array_search($channel->channel_id, array_column($guildChannels, 'id')); ?>
            <?php if (!$key) continue; ?>
            <tr class="itemrow">
                <td class="itemdesc">#<?= $n + 1 ?></td>
                <td class="itemdesc bold" style="min-width:180px;"><?= $this->Html->link('#'.$guildChannels[$key]['name'], ['controller' => 'Stats', 'action' => 'channel', $guildChannels[$key]['id']]) ?></td>
                <td class="itemdesc"><?= $channel->count ?></td>
                <td class="itemdesc" style="min-width:180px;word-break:break-word;"><?= $this->Html->link($this->Discord->getUsernameWithColor($channel->most_active) ?? $channel->most_active, ['controller' => 'Stats', 'action' => 'user', $channel->most_active], ['escape' => false]) ?></td>
                <?php
                $user = substr($channel->random_message, 0, strpos($channel->random_message, '>>'));
                $line = substr($channel->random_message, strpos($channel->random_message, $user) + strlen($user) + 3);
                $user = str_replace(['<', '>', '@', '!'], '', $user);
                ?>
                <td class="itemdesc" style="max-width:600px;word-break:break-word;">
                    <div class="rich-line">
                        <?= '&lt;'.$this->Html->link($this->Discord->getUsernameWithColor($user), ['controller' => 'Stats', 'action' => 'user', $user], ['escape' => false]).'&gt; ' ?> <?= $this->Log->richLine($line) ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
