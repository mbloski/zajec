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
                        <td class="itemdesc"><?= $this->Html->link($this->Discord->getUsernameWithColor($row->author_id) ?? $row->author_id, ['controller' => 'Stats', 'action' => 'user', $row->author_id], ['escape' => false]) ?></td>
                        <td class="itemdesc"><?= h($row->count) ?></td>
                        <td class="itemdesc"><?= h((new \Cake\I18n\Time($row->seen))->timeAgoInWords(['end' => '+7 days'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <h3><?= $this->Html->link('Go to log browser', ['controller' => 'Logs', 'action' => 'index']) ?></h3>
            </div>
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
                        data: <?= json_encode(array_map(function($x) { return ['name' => $this->Discord->getUserById($x->author_id, 'user.username') ?? $x->author_id, 'y' => intval($x->count)]; }, $top->toArray())) ?>
                    }],
                    credits: {
                        enabled: false,
                    }
                });
            </script>
        </div>
    </div>
</div>
