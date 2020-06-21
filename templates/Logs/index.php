<section id="channels">
    <section id="calendar">
        <?= $this->Calendar->render(strtotime($date), $this->getRequest()) ?>
    </section>
    <ul class="chanlist">
        <li class="current">
            <?= $this->Html->link('Home', ['controller' => 'Logs', 'action' => 'index']) ?>
        </li>
        <?php $currentChannel = null; ?>
        <?php foreach ($guildChannels as $group): ?>
            <div class="row">
                <?php if (!empty($group['name'])): ?>
                <?php $class = 'withline'; ?>
                        <li class=""><?= h($group['name']) ?></li>
                <?php endif; ?>
                <div class="list">
                    <ul class="<?= $class ?? '' ?>">
                        <?php foreach ($group['channels'] as $channel): ?>
                        <?php $current = ($this->request->getQuery('channel') == $channel['id'])? 'current' : '' ?>
                        <?php if ($current) $currentChannel = $channel; ?>
                            <li class="<?= $current ?>"><?= $this->Html->link('#'.$channel['name'], ['?' => ['channel' => $channel['id'], 'date' => $date]]) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </ul>
    <ul>
        <li>
            <?= $this->Html->link('Statistics', ['controller' => 'Stats', 'action' => 'index']) ?>
        </li>
    </ul>
</section>
<div id="title">
    <?php if (isset($logs) && $currentChannel): ?>
    #<?= h($currentChannel['name']) ?> - <?= $this->request->getQuery('date') ?? date('Y-m-d') ?>
    <?php else: ?>
    Home
    <?php endif; ?>
</div>
<div id="log">
    <?php if (isset($logs)): ?>
        <?php if ($logs->count() > 0): ?>
            <?php foreach ($logs as $log): ?>
                <?php $this->Log->chat($guildMembers, $log); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php $this->Log->status('Nothing here'); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $this->Log->status('Welcome to ZAJEC log browser'); ?>
        <?php $this->Log->status('Please select a channel.'); ?>
    <?php endif; ?>
</div>
