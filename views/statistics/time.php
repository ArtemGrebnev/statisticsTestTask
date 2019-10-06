
<?php
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>
<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',  'method' => 'post', 'data-pjax' => true]]) ?>
<?=  $form->errorSummary($model); ?>
<?= $form->field($model, 'from')->widget(DatePicker::classname(), [
	'options' => ['placeholder' => Yii::t('app', 'Выберите дату')],
	'attribute2'=>'to',
	'type' => DatePicker::TYPE_RANGE,
	'separator' => 'до',
	'pluginOptions' => [
		'autoclose' => true,
		'startView'=>'year',
		'minViewMode'=>'months',
		'format' => 'yyyy-mm'
	]
]) ?>

<div class="form-group"><?= Html::submitButton('Получить статистику', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']) ?></div>
<? if ($statistics) : ?>
    <div class="container">
        <h2>Данные за период <?= ($model->from . '-' . $model->to)  ?></h2>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">Общая сумма </div>
                <div class="panel-body"><?= $statistics['totalAmount'] ?: 'Нет данных за данный период' ?></div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">Средняя</div>
                <div class="panel-body"><?= $statistics['average'] ?: 'Нет данных за данный период' ?></div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">Самая большая</div>
                <div class="panel-body"><?= $statistics['maxTotal'] ?: 'Нет данных за данный период' ?></div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Медианная сумма</div>
                <div class="panel-body"><?= $statistics['median'] ?: 'Нет данных за данный период' ?></div>
            </div>
        </div>
    </div>
<? endif; ?>

<?php ActiveForm::end() ?>
<?php Pjax::end(); ?>
