<section id="channels">
    <section id="calendar">
        <?= $this->Calendar->render(strtotime($date), $this->getRequest()) ?>
    </section>
    <ul class="chanlist">
        <li class="current">
            <?= $this->Html->link('Home', ['controller' => 'Logs', 'action' => 'index']) ?>
        </li>
        <?php $currentChannel = null; ?>
        <?php foreach ($categorizedGuildChannels as $group): ?>
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
    <div class="chantitle">
        #<?= h($currentChannel['name']) ?> -
        <?php if ($this->request->getQuery('search')): ?>
            Search results
        <?php else: ?>
            <?= $this->request->getQuery('date') ?? date('Y-m-d') ?>
        <?php endif; ?>
    </div>
    <?php else: ?>
        Home
        <?php if ($this->request->getQuery('search')): ?>
            - Search results
        <?php endif; ?>
    <?php endif; ?>
    <div class="search">
        <?= $this->Form->create($logs, ['type' => 'get']) ?>
        <?php if ($this->request->getQuery('channel') !== null): ?>
            <?= $this->Form->input('channel', ['type' => 'hidden', 'value' => $this->request->getQuery('channel')]) ?>
        <?php endif; ?>
        <?= $this->Form->input('search') ?>
        <?= $this->Form->submit('Search') ?>
        <?= $this->Form->end() ?>
    </div>
</div>
<div id="log">
    <?php if (isset($logs) && $currentChannel || $this->request->getQuery('search')): ?>
        <?php if ($logs->count() > 0): ?>
            <?php foreach ($logs as $log): ?>
                <?php $this->Log->chat($log, (bool)$this->request->getQuery('search'), $this->request->getQuery('channel') === null); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php $this->Log->status('Nothing here'); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $this->Log->status('Welcome to ZAJEC log browser'); ?>
        <?php $this->Log->status('Please select a channel.'); ?>
    <?php endif; ?>
</div>
