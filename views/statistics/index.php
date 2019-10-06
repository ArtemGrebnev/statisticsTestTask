<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StatisticsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Statistics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statistics-index">

    <h1>Statistics table </h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'id',
			[
				'attribute' => 'month',
				'value' => function ($model) {
					$date = \DateTime::createFromFormat('Y-m-d', $model->month);
					return $date->format('Y-m');
				}],
			'user_id',
			'money',

		],

	]); ?>


</div>
