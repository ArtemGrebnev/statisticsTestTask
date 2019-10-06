<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput([
        'accept'=> '.csv',
        'class' => 'btn btn-lg btn-primary'
    ])
?>
    <button class="btn btn-lg btn-success">Подтвердить</button>

<?php ActiveForm::end() ?>