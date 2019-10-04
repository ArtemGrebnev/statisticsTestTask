<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Statistics */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="statistics-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
    ]) ?>
</div>
