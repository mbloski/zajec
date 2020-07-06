<?= $this->Html->script('highcharts') ?>

<div>
    <div class="sbox" style="text-align: center;">
        <h1><?= h($user['user']['username']) ?> user statistics</h1>
    </div>
</div>

<?= $this->Element('stats/activity') ?>

<div>
    <div class="sbox" style="text-align: center;">
        <div style="display: inline-block;" style="width:50%;">
            <h2>Most commonly mentioned users</h2>
            <table class="itemtable">
                <thead>
                <th>Username</th>
                <th>Mentions</th>
                </thead>
                <tbody>
                <?php foreach ($mostMentioned as $n => $row): ?>
                    <?php if ($n > 9) break; ?>
                    <tr class="itemrow">
                        <td class="itemdesc"><?= $this->Html->link($this->Discord->getUsernameWithColor($row->mentioned_id) ?? $row->mentioned_id, ['controller' => 'Stats', 'action' => 'user', $row->mentioned_id], ['escape' => false]) ?></td>
                        <td class="itemdesc"><?= h($row->mentions) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>

        <div style="display: inline-block;" style="width:50%;">
            <h2>Most commonly mentioned by</h2>
            <table class="itemtable">
                <thead>
                <th>Username</th>
                <th>Mentions</th>
                </thead>
                <tbody>
                <?php foreach ($mostMentionedBy as $n => $row): ?>
                    <?php if ($n > 9) break; ?>
                    <tr class="itemrow">
                        <td class="itemdesc"><?= $this->Html->link($this->Discord->getUsernameWithColor($row->author_id) ?? $row->author_id, ['controller' => 'Stats', 'action' => 'user', $row->author_id], ['escape' => false]) ?></td>
                        <td class="itemdesc"><?= h($row->mentions) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->Element('stats/reactions') ?>

<div class="sbox">
    <h2 class="text-center">
        Pictures
    </h2>
    <div class="gallery">
        <?php foreach ($pictures as $pic): ?>
        <div class="thumb-wrap">
            <a class="thumb" href="<?= \Cake\Routing\Router::url(['controller' => 'Losers', 'action' => 'photo', $pic->id, basename($pic->url)], true) ?>" target="_blank">
                <img class="thumblink" src="<?= \Cake\Routing\Router::url(['controller' => 'Losers', 'action' => 'thumbnail', $pic->id, 'thumb_'.basename($pic->url)], true) ?>">
            </a>
            <span class="caption"><?= $pic->created ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
