@extends('backend.general.main')

@section('content')
    <div class="box-body">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div id="visits_per_day" class="graph_highchart"></div>
            </div><!-- /.col-md-12 -->
            <div class="clearfix"></div>
            <hr>
            <div class="col-md-12 col-sm-12">
                <div id="referrers" class="graph_highchart"></div>
            </div><!-- /.col-md-12 -->
            <div class="clearfix"></div>
            <hr>
            <div class="col-md-6 col-sm-12">
                <div id="browsers" class="graph_highchart"></div>
            </div><!-- /.col-md-12 -->
            <div class="col-md-6 col-sm-12">
                <div id="sos" class="graph_highchart"></div>
            </div><!-- /.col-md-12 -->
            <div class="clearfix"></div>
        </div><!-- /.row -->
    </div><!-- /.box-body -->
@endsection

@section('css_header')
    <style>
        .graph_highchart{
            width:100%;
            height:400px;
        }
    </style>
@endsection

@section('js_footer')
    <?=plugins_js_dir('highcharts-5.0.7/code/highcharts.js')."\n"; ?>
    <?=plugins_js_dir('highcharts-5.0.7/code/modules/drilldown.js')."\n"; ?>
    <script>
        $(document).ready(function()
        {
            Highcharts.setOptions({
                lang: {
                    drillUpText: 'Regresar a {series.name}'
                }
            });

            Highcharts.chart('visits_per_day', {
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Visitas por día'
                },

                subtitle: {
                    text: 'Últimos 10 días'
                },

                xAxis: {
                    categories: JSON.parse('<?=json_encode(array_column($visits,'date'));?>')
                },

                yAxis: [
                    { // left y axis
                        title: {
                            text: null
                        },
                        labels: {
                            align: 'left',
                            x: 3,
                            y: 16,
                            format: '{value:.,0f}'
                        },
                        showFirstLabel: false
                    },
                    { // right y axis
                        linkedTo: 0,
                        gridLineWidth: 0,
                        opposite: true,
                        title: {
                            text: null
                        },
                        labels: {
                            align: 'right',
                            x: -3,
                            y: 16,
                            format: '{value:.,0f}'
                        },
                        showFirstLabel: false
                    }
                ],

                legend: {
                    align: 'left',
                    verticalAlign: 'top',
                    y: 20,
                    floating: true,
                    borderWidth: 0
                },

                tooltip: {
                    shared: true,
                    crosshairs: true
                },

                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function (e) {

                                }
                            }
                        },
                        marker: {
                            lineWidth: 1
                        }
                    }
                },

                series: [
                    {
                        name: 'Todas',
                        lineWidth: 4,
                        marker: {
                            symbol: 'square'
                        },
                        data: JSON.parse('<?=json_encode(array_column($visits,'total'));?>')

                    },
                    {
                        name: 'Visitantes',
                        lineWidth: 4,
                        marker: {
                            symbol: 'diamond'
                        },
                        data: JSON.parse('<?=json_encode(array_column($visits,'unique'));?>')
                    },
                    {
                        name: 'Nuevos Visitantes',
                        lineWidth: 4,
                        marker: {
                            radius: 4
                        },
                        data: JSON.parse('<?=json_encode(array_column($visits,'new'));?>')
                    },
                    {
                        name: 'Visitantes Regulares',
                        lineWidth: 4,
                        marker: {
                            symbol: 'triangle-down'
                        },
                        data: JSON.parse('<?=json_encode(array_column($visits,'regulars'));?>')
                    },
                ]
            });

            Highcharts.chart('referrers', {
                credits: {
                    enabled: false
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Referidos'
                },
                subtitle: {
                    text: 'Top de Páginas Web desde donde te visitan.'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    showFirstLabel: false
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}%'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.title}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                },

                series: [{
                    name: 'Referidos',
                    colorByPoint: true,
                    data: JSON.parse('<?=json_encode($referrers['data']);?>')
                }],
                drilldown: {
                    series: JSON.parse('<?=json_encode($referrers['drilldown']);?>')
                }
            });

            Highcharts.chart('browsers', {
                credits: {
                    enabled: false
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Navegadores'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Visitas',
                    colorByPoint: true,
                    data: JSON.parse('<?=json_encode($browsers);?>')
                }]
            });

            // Radialize the colors
            Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {
                        cx: 0.5,
                        cy: 0.3,
                        r: 0.7
                    },
                    stops: [
                        [0, color],
                        [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                    ]
                };
            });

            Highcharts.chart('sos', {
                credits: {
                    enabled: false
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Sistemas Operativos'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            },
                            connectorColor: 'silver'
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Visitas',
                    colorByPoint: true,
                    data: JSON.parse('<?=json_encode($sos);?>')
                }]
            });
        });
    </script>
@endsection