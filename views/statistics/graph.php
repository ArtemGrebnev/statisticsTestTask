<?php
use dosamigos\chartjs\ChartJs;
?>

<?= ChartJs::widget([
	'type' => 'line',
	'options' => [
		'height' => 400,
		'width' => 400
	],
	'data' => [
		'labels' => $arrMonthAndSum['month'],
		'datasets' => [
			[
				'label' => "SUM",
				'backgroundColor' => "rgba(179,181,198,0.2)",
				'borderColor' => "rgba(179,181,198,1)",
				'pointBackgroundColor' => "rgba(179,181,198,1)",
				'pointBorderColor' => "#fff",
				'pointHoverBackgroundColor' => "#fff",
				'pointHoverBorderColor' => "rgba(179,181,198,1)",
				'data' => $arrMonthAndSum['total_money']
			],
			[
				'label' => "AVG MONEY",
				'backgroundColor' => "rgba(255,99,132,0.2)",
				'borderColor' => "rgba(255,99,132,1)",
				'pointBackgroundColor' => "rgba(255,99,132,1)",
				'pointBorderColor' => "#fff",
				'pointHoverBackgroundColor' => "#fff",
				'pointHoverBorderColor' => "rgba(255,99,132,1)",
				'data' => $arrMonthAndSum['avg_money']
			],
		]
	]
]);
?>