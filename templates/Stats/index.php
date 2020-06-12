<?= $this->Html->script('highcharts') ?>

<div class="sbox">
    <figure class="highcharts-figure">
        <div id="top"></div>
    </figure>
    <script type="text/javascript">
        Highcharts.chart('top', {
            title: {
                text: 'Top users by activity'
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
