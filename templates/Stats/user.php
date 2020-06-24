<?php

use Cake\Utility\Hash;

?>
<?= $this->Html->script('highcharts') ?>

<div>
    <div class="sbox" style="text-align: center;">
        <h1><?= h($user['user']['username']) ?> user statistics</h1>
    </div>
</div>

<div class="row">
    <div class="column">
        <figure class="highcharts-figure">
            <div id="days"></div>
        </figure>
        <script type="text/javascript">
            Highcharts.chart('days', {
                colors: ['#4572A7', '#AA4643', '#89A54E', '#80699B'],
                chart: {
                    backgroundColor: 'transparent',
                    type: 'column',
                    credits: false,
                },
                legend: {
                    reversed: true,
                },
                title: {
                    text: 'Daily activity (last 2 weeks)'
                },
                xAxis: {
                    categories: <?= json_encode(array_keys($dailyActivity)) ?>
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total lines'
                    }
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                    shared: true
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    },
                    series: {
                        pointPadding: 0,
                        groupPadding: 0,
                        states: {
                            inactive: {
                                opacity: 0.6,
                            }
                        }
                    },
                },
                series: [{
                    name: '18-23',
                    data: <?= json_encode(Hash::extract($dailyActivity, '{s}.18')) ?>
                }, {
                    name: '12-17',
                    data: <?= json_encode(Hash::extract($dailyActivity, '{s}.12')) ?>
                }, {
                    name: '6-11',
                    data: <?= json_encode(Hash::extract($dailyActivity, '{s}.6')) ?>
                }, {
                    name: '0-5',
                    data: <?= json_encode(Hash::extract($dailyActivity, '{s}.0')) ?>
                }],
                credits: {
                    enabled: false,
                }
            });
        </script>
    </div>

    <div class="column">
        <figure class="highcharts-figure">
            <div id="hours"></div>
        </figure>
        <script type="text/javascript">
            Highcharts.chart('hours', {
                colors: ['#4572A7', '#AA4643', '#89A54E', '#80699B'],
                chart: {
                    backgroundColor: 'transparent',
                    type: 'spline',
                    credits: false,
                },
                title: {
                    text: 'Most active times'
                },
                xAxis: {
                    categories: <?= json_encode(array_keys($mostActiveTimes)) ?>,
                    labels: {
                        step: 1,
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total lines'
                    }
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    },
                    series: {
                        pointPadding: 0,
                        groupPadding: 0,
                        states: {
                            inactive: {
                                opacity: 0.6,
                            }
                        }
                    },
                },
                series: [{
                    name: 'lines',
                    data: <?= json_encode(array_values($mostActiveTimes)) ?>,
                }],
                credits: {
                    enabled: false,
                }
            });
        </script>
    </div>
</div>

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
                        <td class="itemdesc discordfeel"><?= $this->Html->link($this->Discord->getUsernameWithColor($row->mentioned_id) ?? $row->mentioned_id, ['controller' => 'Stats', 'action' => 'user', $row->mentioned_id], ['escape' => false]) ?></td>
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
                        <td class="itemdesc discordfeel"><?= $this->Html->link($this->Discord->getUsernameWithColor($row->author_id) ?? $row->author_id, ['controller' => 'Stats', 'action' => 'user', $row->author_id], ['escape' => false]) ?></td>
                        <td class="itemdesc"><?= h($row->mentions) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="sbox">
    <h2 class="text-center">
        Most popular reactions
    </h2>
    <table class="itemtable">
        <tr class="itemrow solid-border">
            <?php foreach ($reactions as $reaction): ?>
                <td class="itemgfx" style="width: 0;">
                    <div><?= $this->Number->format($reaction->count) ?>x</div>
                </td>
                <td class="itemgfx">
                    <span class="emoji emoji-32">
                        <?php
                        $emoji = str_replace(['<:', ':>'], '', $this->Discord->resolveEmoji($reaction->reaction));
                        echo $this->Twemoji->replace($emoji);
                        ?>
                    </span>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
</div>

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
