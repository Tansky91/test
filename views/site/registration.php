<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\User */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-sm-offset-2 col-sm-10">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'registration-form',
        'enableAjaxValidation' => true,
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-offset-2 col-sm-10\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput() ?>

    <?= $form->field($model, 'password_hash')->textInput() ?>

    <?= $form->field($model, 'confirmPassword')->textInput() ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>