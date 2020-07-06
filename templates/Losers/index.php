<?php foreach ($losers as $user => $pictures): ?>
<div class="sbox">
    <h2 class="text-center">
        <?= $this->Html->link($this->Discord->getUserById($user, 'user.username'), ['controller' => 'Stats', 'action' => 'user', $user]) ?>
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
<?php endforeach; ?>
