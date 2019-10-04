
<?php
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
use yii\helpers\Html;
?>
<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',  'method' => 'post', 'data-pjax' => true]]) ?>
<?=  $form->errorSummary($model); ?>
<?php
echo '<div class="input-group drp-container">';
echo DateRangePicker::widget([
	'model'=>$model,
	'attribute' => 'date_ranges',
	'convertFormat'=>true,
	'startAttribute' => 'to',
	'endAttribute' => 'from',
	'pluginOptions'=>[
		'locale'=>['format' => 'Y-m'],
	]
]) ;
echo '</div>'; ?>

<div class="form-group"><?= Html::submitButton('Получить статистику', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']) ?></div>
<? if ($flag) : ?>
    <div class="container">
        <h2>Данные за период <?= $model->date_ranges ?></h2>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">Общая сумма </div>
                <div class="panel-body"><?= $totalAmount ?: 'Нет данных за данный период' ?></div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">Средняя</div>
                <div class="panel-body"><?= $average ?: 'Нет данных за данный период' ?></div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">Самая большая</div>
                <div class="panel-body"><?= $maxTotal ?: 'Нет данных за данный период' ?></div>
            </div>
        </div>
    </div>
<? endif; ?>

<?php ActiveForm::end() ?>
<?php Pjax::end(); ?>
