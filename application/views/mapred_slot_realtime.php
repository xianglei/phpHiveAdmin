<div class="span4">
<script type="text/javascript">
/*$(function () {
	var chart = new Highcharts.Chart({
		chart: {
			backgroundColor: "#FFFFFF",
			renderTo: 'mapred_slot_realtime',
			type: 'gauge',
			plotBorderWidth: 1,
			plotBackgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0, '#FFF4C6'],
					[0.3, '#EFFFFF'],
					[1, '#FFF4C6']
				]
			},
			plotBackgroundImage: null,
			height: 200
		},
	
		title: {
			text: '<?php echo $common_mr_slots_used;?>'
		},
		
		pane: [{
			startAngle: -45,
			endAngle: 45,
			background: null,
			center: ['25%', '145%'],
			size: 300
		}, {
			startAngle: -45,
			endAngle: 45,
			background: null,
			center: ['75%', '145%'],
			size: 300
		}],						
	
		yAxis: [{
			min: 0, //-20,
			max: <?php echo $maxMapTasks;?>, //6,
			minorTickPosition: 'outside',
			tickPosition: 'outside',
			labels: {
				rotation: 'auto',
				distance: 20
			},
			plotBands: [{
				from: 0,
				to: 0,
				color: '#C02316',
				innerRadius: '100%',
				outerRadius: '105%'
			}],
			pane: 0,
			title: {
				text: '<?php echo $common_using;?><br/><span style="font-size:8px"><?php echo $common_map_slots;?></span>',
				y: -40
			}
		}, {
			min: 0, //-20,
			max: <?php echo $maxReduceTasks;?>, //6,
			minorTickPosition: 'outside',
			tickPosition: 'outside',
			labels: {
				rotation: 'auto',
				distance: 20
			},
			plotBands: [{
				from: 0,
				to: 0,
				color: '#C02316',
				innerRadius: '100%',
				outerRadius: '105%'
			}],
			pane: 1,
			title: {
				text: '<?php echo $common_using;?><br/><span style="font-size:8px"><?php echo $common_reduce_slots;?></span>',
				y: -40
			}
		}],
		
		plotOptions: {
			gauge: {
				dataLabels: {
					enabled: false
				},
				dial: {
					radius: '100%'
				}
			}
		},
			
	
		series: [{
			data: [<?php echo $maxMapTasks;?>],
			yAxis: 0
		}, {
			data: [<?php echo $maxReduceTasks;?>],
			yAxis: 1
		}]
	
	},
	
	function(chart) {
		setInterval(function() {
			var left = chart.series[0].points[0];
			var right = chart.series[1].points[0];
			var leftVal;
			var rightVal;

			$.getJSON('<?php echo $this->config->base_url();?>index.php/manage/getclusterstatus/', function(data){
				leftVal =  data.mapTasks;
				rightVal = data.reduceTasks;
				
				left.update(leftVal, false);
				right.update(rightVal, false);
				chart.redraw();
			});
	
		}, 2000);
	
	});
});
*/

//----------------------------
/*var map=0;
var reduce=0;

$(function () {
	$(document).ready(function() {
		Highcharts.setOptions({
			global: {
				useUTC: false
			}
		});

		var chart;
		chart = new Highcharts.Chart({
			chart: {
				backgroundColor: "#FFFFFF",
				renderTo: 'mapred_slots_lines',
				type: 'spline',
				marginRight: 10,
				events: {
					load: function() {

						// set up the updating of the chart each second
						var maps = this.series[0];
						var reduces = this.series[1];
						setInterval(function() {
							var x1 = (new Date()).getTime(), // current time
								y1 = map;
							var x2 = (new Date()).getTime(),
								y2 = reduce;
								
							maps.addPoint([x1, y1], true, true);
							reduces.addPoint([x2, y2], true, true);
							$.getJSON("<?php echo $this->config->base_url();?>index.php/manage/getclusterstatus/", function(data){
								map=data.mapTasks;
								reduce = data.reduceTasks;
							});
						}, 2000);
					}
				}
			},
			title: {
				text: '<?php echo $common_mr_slots_used;?>'
			},
			xAxis: {
				type: 'datetime',
				tickPixelInterval: 120
			},
			yAxis: {
				title: {
					text: '<?php echo $common_value;?>'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}
				]
			},
			tooltip: {
				formatter: function() {
						return '<b>'+ this.series.name +'</b><br/>'+
						Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
						Highcharts.numberFormat(this.y, 2);
				}
			},
			legend: {
				enabled: true
			},
			exporting: {
				enabled: false
			},
			series: [{
				name: '<?php echo $common_map_slots;?>',
				data: (function() {
					// generate an array of random data
					var map = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						map.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return map;
				})()
			},
			{
				name: '<?php echo $common_reduce_slots;?>',
				data: (function() {
					// generate an array of random data
					var reduce = [],
						time = (new Date()).getTime(),
						i;
	
					for (i = -19; i <= 0; i++) {
						reduce.push({
							x: time + i * 1000,
							y: 0//Math.random()
						});
					}
					return reduce;
				})()
			}
			]
		});
	});
	
});*/
</script>
<!--<div id="mapred_slot_realtime" style="width: 600px; height: 300px; "></div>-->
<div id="mapred_slots_lines" style="width: 600px; height: 200px; "></div>
</div>