<?php

use Cake\Utility\Hash;

?>
<?= $this->Html->script('highcharts') ?>

<div>
    <div class="sbox" style="text-align: center;">
        <h2>Top users by activity</h2>
        <div style="display: inline-block;" style="width:50%;">
            <table class="itemtable">
                <thead>
                <th>Username</th>
                <th>Lines</th>
                <th>Last seen</th>
                </thead>
                <tbody>
                <?php foreach ($top->toArray() as $n => $row): ?>
                    <?php if ($n > 9) break; ?>
                    <tr class="itemrow">
                        <td class="itemdesc"><?= h($this->Discord->getUserById($guildMembers, $row->author_id, 'user.username')) ?></td>
                        <td class="itemdesc"><?= h($row->count) ?></td>
                        <td class="itemdesc"><?= h((new \Cake\I18n\Time($row->seen))->timeAgoInWords(['end' => '+7 days'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: inline-block; width:50%;">
            <figure class="highcharts-figure">
                <div id="top"></div>
            </figure>
            <script type="text/javascript">
                Highcharts.chart('top', {
                    colors: ['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'],
                    title: {
                        text: null,
                    },
                    chart: {
                        backgroundColor: 'transparent',
                        type: 'pie',
                        credits: false,
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: false,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        },
                        series: {
                            states: {
                                inactive: {
                                    opacity: 0.6,
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Lines',
                        colorByPoint: true,
                        data: <?= json_encode(array_map(function($x) use ($guildMembers) { return ['name' => $this->Discord->getUserById($guildMembers, $x->author_id, 'user.username'), 'y' => intval($x->count)]; }, $top->toArray())) ?>
                    }],
                    credits: {
                        enabled: false,
                    }
                });
            </script>
        </div>
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
                    categories: <?= json_encode(array_map(function($x) { return $x->hour; }, $mostActiveTimes->toArray())) ?>
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
                    data: <?= json_encode(array_map(function($x) { return intval($x->count); }, $mostActiveTimes->toArray())) ?>,
                }],
                credits: {
                    enabled: false,
                }
            });
        </script>
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
                    <span class="emoji">
                        <?php
                            $emoji = str_replace(['&lt;:', ':&gt;'], '', $this->Discord->resolveEmoji($reaction->reaction, 32, 32));
                            if (mb_strlen($emoji) <= 4) {
                                $emoji = dechex(mb_ord($emoji));
                                $emoji = '<img src="https://abs.twimg.com/emoji/v2/svg/'.$emoji.'.svg" width="32" height="32">';
                            }
                            echo $emoji;
                        ?>
                    </span>
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
</div>

<div class="sbox">
    <h2 class="text-center">
        Quotes
        <img src="https://discordapp.com/assets/0b6fc9f58ca3827977d546a6ee0ca3e7.svg" class="emoji" alt=":speech_balloon:">
    </h2>
    <table class="itemtable">
        <thead>
        <th>ID</th>
        <th>Author</th>
        <th>Quote</th>
        <th width="170px;">Date</th>
        </thead>
        <tbody>
        <?php foreach ($quotes as $n => $quote): ?>
            <tr class="itemrow">
                <td class="itemdesc">#<?= $n + 1 ?></td>
                <td class="itemdesc" style="width:200px;"><?= h($quote->name) ?></td>
                <td class="itemdesc" style="max-width:600px;word-break:break-word;"><?= h($quote->value) ?></td>
                <td><?= h($quote->created) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
