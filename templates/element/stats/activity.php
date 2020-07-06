<?php
use Cake\Utility\Hash;
?>

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
