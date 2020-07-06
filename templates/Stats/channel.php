<?= $this->Html->script('highcharts') ?>

<div>
    <div class="sbox" style="text-align: center;">
        <h1>#<?= h($channel['name']) ?> channel statistics</h1>
    </div>
</div>

<?= $this->Element('stats/top_users') ?>
<?= $this->Element('stats/activity') ?>
